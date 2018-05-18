
import Vue from 'vue';
import VueSweetAlert from 'vue-sweetalert';
import InfiniteLoading from 'vue-infinite-loading';
import axios from 'axios';

Vue.component('model',
{
    props: ['profile'],
    data() {
        return {
            showList: true,
            showModule: '1',
            //1 = Dashboard
            //2 = Profile
            //3 = Locations

            url: '',
            total: 0,
            skip: 0,
            pageSize: 100,

            list: [],
            filterListBy: 1,
        }
    },

    methods:
    {
        showModules($moduleID)
        {
            var app = this;
            app.showModule = $moduleID;
            app.showList = 1;
        },

        infiniteHandler($state)
        {
            var app = this;

            if (app.url != '')
            {
                axios.get('/api/' + this.profile + '/back-office/list/'  + app.skip + '/' + app.url + '/' + app.filterListBy,
                {
                    params:
                    {
                        page: app.list.length / this.pageSize + 1,
                    },
                })
                .then(({ data }) =>
                {
                    if (data.length > 0)
                    {
                        for (let i = 0; i < data.length; i++)
                        {
                            app.list.push(data[i]);
                        }

                        app.skip += app.pageSize;
                        $state.loaded();
                    }
                    else
                    {
                        $state.complete();
                    }
                })
                .catch(error => {
                    //Stop loading with one error.
                    $state.complete();
                    //Log error for help
                    console.log(error);
                    this.$swal('Error trying to load records.');
                });
            }
        },

        //This restarts the inifity loader.
        onList($url, $filter)
        {
            var app = this;
            app.showList = true;
            app.skip = 0;
            app.url = $url;
            app.showModule = $filter;

            app.list = [];

            if (app.$refs.infiniteLoading != null)
            {
                app.$refs.infiniteLoading.attemptLoad();
            }
        },

        onCreate()
        {
            var app = this;
            app.showList = false;
        },

        onEdit($data)
        {
            var app = this;
            app.showList = false;
            axios.get('/api/' + this.profile + '/back-office/' + app.url + '/' + $data.id + '/edit')
            .then(({ data }) =>
            {
                app.$children[0].onEdit(data);
            })
            .catch(error => {
                console.log(error);
                this.$swal('Error trying to edit record.');
            });
        },

        onCancel($data)
        {
            var app = this;
            swal({
                title: 'Are you sure?',
                text: "This will cancel all changes made",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, cancel it!'
            }).then((result) => {
                //clean property changes
                app.showList = true;
            })
        },

        onSave($url, $data)
        {
            var app = this;
            axios.post($url, $data)
            .then(() =>
            {
                app.showList = true;
                this.$swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Awsome! Your work has been saved',
                    showConfirmButton: false,
                    timer: 1500
                })
                if (app.$refs.infiniteLoading != null)
                {
                    app.$refs.infiniteLoading.attemptLoad();
                }

            })
            .catch(error => {
                console.log(error.response);
                this.$swal('Error trying to save record.');
            });
        },

        onSaveCreate($url, $data)
        {
            var app = this;

            axios.post($url, $data)
            .then(() =>
            {
                //TODO run code to clean data.
                this.$swal({
                    position: 'top-end',
                    type: 'success',
                    title: 'Awsome! Your work has been saved',
                    showConfirmButton: false,
                    timer: 1500
                })

                app.showList = true;
            })
            .catch(error => {
                console.log(error);
                this.$swal('Error trying to save record.');
            });
        },

        onDelete($url, $data)
        {
            var app = this;

            swal({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'danger',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            })
            .then((result) => {
                axios.delete('/api/' + this.profile + '/back-office/' + this.url, {
                    params: { $data: this.data.ID }
                })
                .then(() => {

                    let index = this.list.findIndex(crud => list.ID === ID);

                    this.list.splice(index, 1);

                    this.$swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'The record has been deleted',
                        showConfirmButton: false,
                        timer: 750
                    })
                })
                .catch(error => {
                    console.log(error);
                    this.$swal('Error trying to delete record.');
                });
            })
        },

        onApprove($data)
        {
            var app = this;

            swal({
                title: 'You are about to Approve this record.',
                text: "This will process your record, and is non-reversable.",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, approve it!'
            }).then((result) => {

                axios.post('/api/' + this.profile + '/back-office/' + app.url + '/' + $data.id + '/approve', $data)
                .then(() =>
                {
                    this.$swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Awsome! Your record has been approved',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    app.showList = true;
                })
                .catch(error => {
                    console.log(error);
                    this.$swal('Error trying to approve record.');
                });
                //Code to approve
            })
        },

        onAnnull($url, $data)
        {
            var app = this;

            swal({
                title: 'Annull? Are you sure?',
                text: "This is non-reversable",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, annull it!'
            }).then((result) => {
                //Code to annull
                axios.post('/api/' + this.profile + '/back-office/' + app.url + '/' + $data.id + '/annull', $data)
                .then(() =>
                {
                    this.$swal({
                        position: 'top-end',
                        type: 'success',
                        title: 'Awsome! Your record has been annulled',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    app.showList = true;
                })
                .catch(error => {
                    console.log(error);
                    this.$swal('Error trying to annull record.');
                });
            })
        }
    },

    mounted: function mounted()
    {

    }
});
