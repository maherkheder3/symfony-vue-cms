<template>
    <div class="body-container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <router-link class="navbar-brand" to="/home">App</router-link>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <router-link class="nav-item" tag="li" to="/home" active-class="active">
                            <a class="nav-link">Home</a>
                        </router-link>
                        <router-link class="nav-item" tag="li" to="/posts" active-class="active">
                            <a class="nav-link">Posts</a>
                        </router-link>
                        <router-link v-if="isAuthenticated" class="nav-item" tag="li" to="/security/logout" active-class="active">
                            <a class="nav-link">Logout</a>
                        </router-link>
                        <router-link v-else class="nav-item" tag="li" to="/security/login" active-class="active">
                            <a class="nav-link">Login</a>
                        </router-link>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        name: 'app',
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
            }
        },
    }
</script>