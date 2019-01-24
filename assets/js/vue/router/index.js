import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';
import Home from '../views/Home';
import Login from '../views/Login';
import Posts from '../views/Posts';
import Backend from "../views/Admin/Backend";
import Create from "../views/Admin/post/Create";
import Show from "../views/post/Show";

Vue.use(VueRouter);

let router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: Home },
        { path: '/security/login', component: Login },
        { path: '/admin/backend', component: Backend },

        // Post
        { path: '/admin/post/create', component: Create },
        { path: '/posts', component: Posts },
        { path: '/posts', component: Posts, meta: { requiresAuth: true } },
        { path: '/post/show', component: Show },

        { path: '*', redirect: '/' }
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