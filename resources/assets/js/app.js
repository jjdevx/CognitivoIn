
/**
* First we will load all of this project's JavaScript dependencies which
* includes Vue and other libraries. It is a great starting point when
* building robust, powerful web applications using Vue and Laravel.
*/
window.Vue = require('vue');

import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
import VueRouter from 'vue-router';
import VueResource from 'vue-resource';
import Buefy from 'buefy';

import 'buefy/lib/buefy.css';

require('./bootstrap');
require('./components/bootstrap');

window.Vue.use(VueResource);
window.Vue.use(VueRouter);
Vue.use(require('vue-shortkey'))
Vue.use(VueSweetalert2);
Vue.use(Buefy);

Vue.prototype.lang = (key) => {
    return _.get(window.trans, key, key);
};

/**
Back-Office views to be used for Ajax Loaded sites.
*/

import Dashboard from './views/Dashboard';
import NotFoundComponent from './views/NotFoundComponent';

import ProfileForm from './views/config/ProfileForm';
import Location from './views/config/Location';
import SalesTax from './views/config/SalesTax';
import Contract from './views/config/Contract';
import TeamMember from './views/config/TeamMember';

import Crm_Dashboard from './views/crm/Dashboard';
import Pipelines from './views/crm/Pipeline';
import Opportunities from './views/crm/Opportunity';
import OpportunityForm from './views/crm/OpportunityForm';
import OpportunityShow from './views/crm/OpportunityShow';

//import Sales_Dashboard from './components/dash';
import Customers from './views/sales/Customer';
import CustomerForm from './views/sales/CustomerForm';
import Orders from './views/sales/Order';
import OrderForm from './views/sales/OrderForm';
import OrderShow from './views/sales/OrderShow';
import Payment from './views/sales/Payment';

import Purchase_Dashboard from './views/purchase/Dashboard';
import Suppliers from './views/purchase/Supplier';
import SupplierForm from './views/purchase/SupplierForm';

import Stock_Dashboard from './views/stock/Dashboard';
import Items from './views/stock/Item';
import StockMovement from './views/stock/StockMovement';
import StockMovementForm from './views/stock/StockMovementForm';
import ItemForm from './views/stock/ItemForm';
import Promotion from './views/stock/Promotion';
import PromotionForm from './views/stock/PromotionForm';

import Finance_Dashboard from './views/finance/Dashboard';
import Accounts from './views/finance/Account';
import AccountForm from './views/finance/AccountForm';
import AccountPayables from './views/finance/AccountPayable';
import AccountReceivables from './views/finance/AccountReceivable';
import AccountMovements from './views/finance/AccountMovement';
import AccountMovementForm from './views/finance/AccountMovementForm';

/**
* Next, we will create a fresh Vue application instance and attach it to
* the page. Then, you may begin adding components to this application
* or customize the JavaScript scaffolding to fit your unique needs.
*/

Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

const MainView = {
    template: `<div class="us">
    <router-view/>
    </div>`
}

//Note: These tasks are only needed to show views.
const router = new VueRouter({
    mode:'history',

    routes: [
        //This will cause 404 Errors to be redirected to proper site.
        //{ path: '/*', component: UserSettings },
        {
            path: '/dashboard/:profile',
            component: MainView,
            default:Dashboard,
            children: [

                {
                    path: 'config-profile',
                    component: ProfileForm,
                    name: 'profile.form'
                },

                {
                    path: 'config-locations',
                    component: Location,
                    name: 'location.index'
                },
                {
                    path: 'config-sales-taxes',
                    component: SalesTax,
                    name: 'sales-taxes.index'
                },
                {
                    path: 'config-contracts',
                    component: Contract,
                    name: 'contracts.index'
                },
                {
                    path: 'config-team-members',
                    component: TeamMember,
                    name: 'team-members.index'
                },
                {
                    path: 'config-crm-dashboard',
                    component: Dashboard,
                    name: 'crm.dashboard'
                },
                {
                    path: 'config-crm-pipelines',
                    component: Pipelines,
                    name: 'pipeline.index'
                },
                {
                    path: 'config-crm-opportunities-:userid',
                    component: Opportunities,
                    name: 'opportunity.index'
                },
                {
                    path: 'config-crm-opportunity-:id-:user_id',
                    component: OpportunityForm,
                    name: 'opportunity.form'
                },
                {
                    path: 'config-crm-opportunity-:id',
                    component: OpportunityShow,
                    name: 'opportunity.show'
                },
                // {
                //     path: 'sales-dashboard',
                //     component: Sales_Dashboard,
                //     name: 'sales.dashboard'
                // },
                {
                    path:'sales-customers',
                    component: Customers,
                    name: 'customer.index'
                },
                {
                    path: 'sales-customers-:id',
                    component: CustomerForm,
                    name:'customer.form'
                },
                {
                    path: 'sales-orders',
                    component: Orders,
                    name: 'order.index'
                },
                {
                    path: 'sales-order-:id',
                    component: OrderForm,
                    name: 'order.form'
                },
                {
                    path: 'sales-order-show-:id',
                    component: OrderShow,
                    name: 'order.show'
                },

                {
                    path: 'purchases-dashboard',
                    component: Dashboard,
                    name: 'purchase.dashboard'
                },
                {
                    path: 'purchases-suppliers',
                    component: Suppliers,
                    name: 'supplier.index'
                },
                {
                    path: 'purchases-suppliers-:id',
                    component: SupplierForm,
                    name: 'supplier.form'
                },
                {
                    path: 'stocks-dashboard',
                    component: Dashboard,
                    name: 'stock.dashboard'
                },
                {
                    path: 'stocks-items',
                    component: Items,
                    name: 'item.index'
                },
                {
                    path: 'stocks-movement',
                    component: StockMovement,
                    name: 'stockmovement.index'
                },
                {
                    path: 'stocks-movement-form',
                    component: StockMovementForm,
                    name: 'stockmovement.form'
                },
                {
                    path: 'stocks-item-:id',
                    component: ItemForm,
                    name:'item.form'
                },
                {
                    path: 'stocks-itempromotions',
                    component: Promotion,
                    name: 'itempromotion.index'
                },
                {
                    path: 'stocks-itempromotions-:id',
                    component: PromotionForm,
                    name: 'itempromotion.form'
                },
                {
                    path: 'finances-dashboard',
                    component: Dashboard,
                    name: 'finance.dashboard'
                },
                {
                    path: 'finances-account-payables',
                    component: AccountPayables,
                    name: 'account-payable.index'
                },

                {
                    path: 'finances-account-recievables',
                    component: AccountReceivables,
                    name: 'account-recievable.index'
                },
                {
                    path: 'finances-account-movement-from',
                    component: AccountMovementForm,
                    name: 'account_movement.form'
                },
                {
                    path: 'finances-account-movements',
                    component: AccountMovements,
                    name: 'account_movement.index'
                },
                {
                    path: 'payment',
                    component: Payment,
                    name: 'payment.index'
                }
            ]
        },
    ]
    // ,
    // components: {
    //     Reports : Reports,
    // }
});

const app = new Vue({
    el: '#app',
    router,
}).$mount('#app');
