<template>
    <v-app dark id="inspire">
        <!--<nav class="navbar navbar-expand-lg navbar-light bg-light">-->
            <!--<div class="container">-->
                <!--<router-link class="navbar-brand" to="/home">App</router-link>-->
                <!--<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">-->
                    <!--<span class="navbar-toggler-icon"></span>-->
                <!--</button>-->
                <!--<div class="collapse navbar-collapse" id="navbarNav">-->
                    <!--<ul class="navbar-nav">-->
                        <!--<router-link class="nav-item" tag="li" to="/home" active-class="active">-->
                            <!--<a class="nav-link">Home</a>-->
                        <!--</router-link>-->
                        <!--<router-link class="nav-item" tag="li" to="/posts" active-class="active">-->
                            <!--<a class="nav-link">Posts</a>-->
                        <!--</router-link>-->
                        <!--<router-link v-if="admin" class="nav-item" tag="li" to="/admin/backend" active-class="active">-->
                            <!--<a class="nav-link">Backend</a>-->
                        <!--</router-link>-->
                        <!--<li v-if="isAuthenticated" class="nav-item" >-->
                            <!--<a class="nav-link" v-on:click="logoutHandeln()">Logout</a>-->
                        <!--</li>-->
                        <!--<router-link v-else class="nav-item" tag="li" to="/security/login">-->
                            <!--<a class="nav-link">Login</a>-->
                        <!--</router-link>-->
                    <!--</ul>-->
                <!--</div>-->
            <!--</div>-->
        <!--</nav>-->

        <v-navigation-drawer app fixed clipped v-model="drawer" >
            <v-toolbar flat class="transparent">
                <v-list dense>
                    <v-list-tile avatar>
                        <v-list-tile-avatar>
                            <img src="https://randomuser.me/api/portraits/men/85.jpg" >
                        </v-list-tile-avatar>
                        <v-list-tile-content>
                            <v-list-tile-title>John Leider</v-list-tile-title>
                        </v-list-tile-content>
                        <v-list-tile-action>
                            <v-btn icon @click.stop="drawer = !drawer">
                                <v-icon>chevron_left</v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                </v-list>
            </v-toolbar>
            <v-list class="pt-0" dense>
                <v-divider></v-divider>
                <v-list-tile v-for="item in items" :key="item.title" :to="item.path">
                    <v-list-tile-action>
                        <v-icon>{{ item.icon }}</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>{{ item.title }}</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>

                <v-list-tile :key="'login-button'" :to="'/admin/backend'">
                    <v-list-tile-action>
                        <v-icon>dashboard</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Backend</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile v-if="isAuthenticated" :key="'logout-button'" v-on:click="logoutHandeln()">
                    <v-list-tile-action>
                        <v-icon>dashboard</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Logout</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
                <v-list-tile v-else :key="'logout-button'" :to="'/security/login'">
                    <v-list-tile-action>
                        <v-icon>dashboard</v-icon>
                    </v-list-tile-action>
                    <v-list-tile-content>
                        <v-list-tile-title>Login</v-list-tile-title>
                    </v-list-tile-content>
                </v-list-tile>
            </v-list>
        </v-navigation-drawer>

        <v-toolbar color="red" dense fixed clipped-left app >
            <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>

            <v-btn flat icon :to="'/home'">
                <v-icon class="mx-3">home</v-icon>
            </v-btn>

            <v-toolbar-title class="mr-5 align-center">
                <span class="title">web-netz</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <!--<v-layout row align-center style="max-width: 650px">-->
                <!--<v-text-field placeholder="Search..." single-line append-icon="search" :append-icon-cb="() => {}" color="white" hide-details></v-text-field>-->
            <!--</v-layout>-->
        </v-toolbar>

        <v-content>
            <v-container fluid :style="justInRouter()">
                <transition>
                    <router-view />
                </transition>
            </v-container>
        </v-content>
        <v-footer app></v-footer>
    </v-app>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'app',
        data () {
            return {
                drawer: true,
                items: [
                    { title: 'Home', icon: 'dashboard', path: '/home' },
                    { title: 'Posts', icon: 'question_answer', path: '/posts'  },
                ],
                mini: true,
                right: null,
                transitionName: null,
                carouselItems: [
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/squirrel.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/sky.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/bird.jpg'
                    },
                    {
                        src: 'https://cdn.vuetifyjs.com/images/carousel/planet.jpg'
                    }
                ]
            }
        },
        created () {
            let isAuthenticated = JSON.parse(this.$parent.$el.attributes['data-is-authenticated'].value),
                roles = JSON.parse(this.$parent.$el.attributes['data-roles'].value);

            let csrf_token = this.$parent.$el.attributes['data-token'];
            let payload = { isAuthenticated: isAuthenticated, roles: roles , csrf_token: csrf_token };
            this.$store.dispatch('security/onRefresh', payload);

            axios.interceptors.response.use(undefined, (err) => {
                return new Promise(() => {
                    if (err.response.status === 403) {
                        this.$router.push({path: '/login'})
                    } else if (err.response.status === 500) {
                        document.open();
                        document.write(err.response.data);
                        document.close();
                    }
                    throw err;
                });
            });


        },
        computed: {
            isAuthenticated () {
                return this.$store.getters['security/isAuthenticated']
            },
            csrf_token () {
                return this.$store.getters['security/csrf_token']
            },
            admin () {
                return this.$store.getters['security/hasRole']('ROLE_ADMIN');
            },

        },
        methods:{
            logoutHandeln(){
                // href="/api/security/logout"
                this.$store.dispatch('security/logout')
                //window.localStorage.removeItem('authuser')
                this.$router.push({path: '/'})
            },
            justInRouter(){
                let style = {};
                if(this.$router.currentRoute.path === "/home")
                {
                    style = {
                        'margin': 0,
                        padding: 0
                    }
                }
                return style;
            }
        },
        // watch: {
        //     '$route' (to, from) {
        //         const toDepth = to.path.split('/').length
        //         const fromDepth = from.path.split('/').length
        //         this.transitionName = toDepth < fromDepth ? 'slide-right' : 'slide-left'
        //     }
        // }
    }
</script>
