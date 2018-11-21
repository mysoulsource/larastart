
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


// imports
window.Vue = require('vue');
///import vue router
import VueRouter from 'vue-router'

import { Form, HasError, AlertError } from 'vform'
//import moment from package for date converting
import moment from 'moment'
import VueProgressBar from 'vue-progressbar'
import Swal from 'sweetalert2'
import Gate from './Gate' //importing Gate class
//end of imports

window.swal = Swal;
// importing vform to handle form request and validation using laravel api
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
// end
//registering progress bar globally
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '5px'
})

//registering sweetalert 2 toast globally
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });
  window.toast = toast;
//creating vue filters globally

//creating a new instance of vue to use event handling to fire an event
window.Fire = new Vue();
Vue.filter('capitalize',function(text){
    return text.charAt(0).toUpperCase() + text.slice(1)
});
Vue.filter('dateChange',function(date){
    return moment().format('MMMM Do YYYY')
});
//
// tell vue we are using vue router
Vue.use(VueRouter)
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
//for testing purposes
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
Vue.component(
    'empty',
    require('./components/Empty.vue')
);
Vue.component('pagination', require('laravel-vue-pagination'));
 ///setting Vue routes. Using let for future changes
let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue') },
    { path: '/profile', component: require('./components/Profile.vue') },
    { path: '/users', component: require('./components/Users.vue') },
    { path: '/developer', component: require('./components/Developer.vue') }
  ]

  //prototyping vue gate
  Vue.prototype.$gate = new Gate(window.user);

const router = new VueRouter({
    mode:'history',
    routes // short for `routes: routes`
})



// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    router
}).$mount('#app')
