
Vue.component('opportunity-member-form',
{
    data() {
        return {
            id: 0,
            profile_id: '',
            opportunity_id: '',
            member: '',
            email: '',
            slug: '',
            profile_img: '',
        }
    },

    methods:
    {
        addMember: function($profileID)
        {
            //code for adding tasks
            var app = this;
            axios.post('/api/'+ app.$parent.profile +'/back-office/opportunities/' + this.opportunityID + '/members/', $profileID)
            .then(({ data }) =>
            {
                app.$parent.list.push({
                    id: data.id,
                    member: data.name,
                    email: data.email,
                    slug: '/' + data.slug,
                    profile_img: data.profile_img,
                    profile_id: data.profile_id,
                    opportunity_id: data.opportunity_id,
                });
            });
        },
    },

    mounted: function mounted()
    {

    }
});
