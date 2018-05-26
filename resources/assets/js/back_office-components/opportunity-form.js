// Vue.use(Buefy.default)

Vue.component('opportunity-form',
{
    data() {
        return {
            id: 0,
            relationship_id: '',
            pipeline_id: '',
            deadline_date: '',
            name: '',
            description: '',
            status: '',
            value: '',
            currency:'',
            is_archived: false,

            pipelines:[],
            tasks: [],
            items: [],
            members: []
        }
    },

    computed:
    {
        activeTasks: function ()
        {
            var app = this;

            return app.tasks.filter(function(i)
            {
                return i.completed == 0
            })
        },

        completedTasks: function ()
        {
            var app = this;
            return app.tasks.filter(function(i)
            {
                return i.completed == 1
            }).sort((a) => new Date(a.date_started))
        },
    },


    methods:
    {
        taskChecked: function(task)
        {
            var app = this;
            var url = '/back-office/' + app.$parent.profile + '/sales/opportunities/' + app.id + '/tasks/checked';

            app.$parent.postSpecial(url, task)
            .then(function(response)
            {
                task.completed = task.completed == true ? false : true;
            });
        },

        editTask: function(task)
        {
            console.log(task);
            var app = this;
            var url = '/back-office/' + app.$parent.profile + '/sales/opportunities/' + app.id + '/tasks';

            app.$parent.postSpecial(url, task)
            .then(function(response)
            { });
        },

        deleteTask: function(task)
        {
            var app = this;
            var url = '/back-office/' + app.$parent.profile + '/sales/opportunities/' + app.id + '/tasks/' + task.id;
            var data =
            {
                id: task.id,
                activity_type: task.activity_type,
                opportunity_id: task.opportunity_id,
                sentiment: task.sentiment,
                reminder_date: task.reminder_date,
                date_started: task.date_started,
                date_ended: task.date_ended,
                title: task.title,
                description: task.description,
                geoloc: task.geoloc,
                completed: task.completed
            }

            app.$parent.deleteSpecial(url)
            .then(function(response)
            {

                let index = app.tasks.findIndex(x => x.id === task.id);
                app.tasks.splice(index, 1);
            });
        },

        onEdit: function(data)
        {
            var app = this;
            app.id = data.id;
            app.relationship_id = data.relationship_id;
            app.$children[0].selectText = data.relationship.customer_alias + '|' + data.relationship.customer_taxid;
            app.pipeline_id = data.pipeline_id;
            app.currency = data.currency;
            app.deadline_date = data.deadline_date;
            app.name = data.name;
            app.description = data.description;
            app.status = data.status;
            app.value = data.value;
            app.is_archived = data.is_archived;
        },


        onShow: function(data)
        {
            var app = this;
            //console.log(data);
            app.id = data.id;
            app.relationship_id = data.relationship_id;
            app.pipeline_stage_id  = data.pipeline_stage_id;
            app.deadline_date = data.deadline_date;
            app.name = data.name;
            app.description = data.description;
            app.status = data.status;
            app.value = data.value;
            app.is_archived = data.is_archived;

            app.tasks = [];
            for (var i = 0; i < data.tasks.length; i++) {
                app.tasks.push({
                    id: data.tasks[i].id,
                    activity_type: data.tasks[i].activity_type,
                    opportunity_id: data.tasks[i].id,
                    sentiment: data.tasks[i].sentiment,

                    reminder_date: data.tasks[i].reminder_date,
                    date_started: new Date(data.tasks[i].date_started),
                    date_ended:  new Date(data.tasks[i].date_ended),

                    title: data.tasks[i].title,
                    description: data.tasks[i].description,
                    geoloc: data.tasks[i].geoloc,
                    completed: data.tasks[i].completed,
                    assigned_to:data.tasks[i].assigned_to
                });
            }

            app.members = [];
            for (var i = 0; i < data.members.length; i++) {
                app.members.push({
                    id: data.members[i].id,
                    name: data.members[i].name,
                    email: data.members[i].email,
                    profile_img: data.members[i].profile_img,
                    slug: '/' + data.members[i].slug
                });
            }

            app.items = [];
            for (var i = 0; i < data.items.length; i++) {
                app.items.push({
                    id: data.items[i].id,
                    name: data.items[i].name,
                    quantity: parseInt(data.items[i].quantity),
                    unit_price: data.items[i].unit_price,
                    vat_id: data.items[i].vat_id,
                    opportunity_id: data.items[i].opportunity_id,
                });
            }
        },

        onReset: function()
        {
            var app = this;

            app.id = 0;
            app.relationship_id = '';
            app.pipeline_stage_id  = '';
            app.deadline_date = '';
            app.name = '';
            app.description = '';
            app.status = '';
            app.value = '';
            app.is_archived = '';

            app.tasks = [];
            app.members = [];
            app.items = [];
        },

        getPipelines: function(data)
        {
            var app = this;
            axios.get('/api/' + app.$parent.profile + '/back-office/list/0/pipelines/1')
            .then(({ data }) =>
            {
                app.pipelines = [];
                for(let i = 0; i < data.length; i++)
                {
                    app.pipelines.push({
                        name: data[i]['name'],
                        id: data[i]['id']
                    });
                }
            });
        },

        Approve: function()
        {
            var app=this;
            app.$parent.onApprove({id:app.id});
        }
    },

    mounted: function mounted()
    {
        this.getPipelines();
    }
});
