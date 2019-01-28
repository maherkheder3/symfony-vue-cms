import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import Home from '../views/Home';
import Login from '../views/Login';
import Posts from '../views/Posts';
import Backend from "../views/Admin/Backend";
import Create from "../views/post/Create";
import details from "../views/post/details";
import Vuetify from 'vuetify'
import colors from 'vuetify/es5/util/colors'
import VeeValidate from 'vee-validate'



Vue.use(VeeValidate)
Vue.use(VueRouter);
Vue.use(Vuetify, {
    theme:{
        primary: colors.red.darken1, // #E53935
        error: colors.red.darken1, // #E53935
        secondary: colors.red.lighten4, // #FFCDD2
        accent: colors.indigo.base, // #3F51B5
        black: '#303030'
    }
});

let router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/home', component: Home },
        { path: '/security/login', component: Login },
        { path: '/admin/backend', component: Backend },

        // Post
        { path: '/admin/post/create', component: Create },
        { path: '/posts', component: Posts },
        { path: '/posts', component: Posts, meta: { requiresAuth: true } },
        { name: 'PostDetails', path: '/post/show/:postId', component: details },

        { path: '*', redirect: '/home' }
    ],
});

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // this route requires auth, check if logged in
        // if not, redirect to login page.
        if (store.getters['security/isAuthenticated']) {
            next();
        } else {
            next({
                path: '/login',
                query: { redirect: to.fullPath }
            });
        }
    } else {
        next(); // make sure to always call next()!
    }
});

export default router;