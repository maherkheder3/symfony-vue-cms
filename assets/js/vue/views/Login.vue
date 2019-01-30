<template>
    <div>
        <div v-if="isLoading">
            <loading></loading>
        </div>

        <div v-else-if="hasError" class="row col">
            <div class="alert alert-danger" role="alert">
                {{ error }}
            </div>
        </div>

        <v-container v-else fluid fill-height>
            <v-layout align-center justify-center>
                <v-flex xs12 sm8 md6>
                    <v-card class="elevation-12">
                        <v-toolbar dark color="primary">
                            <v-toolbar-title>Login form</v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                        <v-card-text>
                            <v-form>
                                <v-text-field
                                        v-model="login"
                                        prepend-icon="person"
                                        :counter="3"
                                        :rules="nameRules"
                                        label="Name"
                                        required
                                ></v-text-field>

                                <v-text-field
                                        v-model="password"
                                        prepend-icon="lock"
                                        :counter="3"
                                        label="Password"
                                        required
                                        type="password"
                                ></v-text-field>
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn @click="performLogin()" :disabled="login.length === 0 || password.length === 0 || isLoading" color="primary">Login</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </div>
</template>

<script>
    import Loading from '../components/Loading'
    export default {
        name: 'login',
        components: {
            Loading
        },
        data () {
            return {
                login: 'jane_admin',
                password: 'kitten',
                nameRules: [
                    v => !!v || 'Name is required',
                    v => (v && v.length <= 10) || 'Name must be less than 10 characters'
                ],
            };
        },
        created () {
            let redirect = this.$route.query.redirect;

            if (this.$store.getters['security/isAuthenticated']) {
                if (typeof redirect !== 'undefined') {
                    this.$router.push({path: redirect});
                } else {
                    this.$router.push({path: '/'});
                }
            }
        },
        computed: {
            isLoading () {
                return this.$store.getters['security/isLoading'];
            },
            hasError () {
                return this.$store.getters['security/hasError'];
            },
            error () {
                return this.$store.getters['security/error'];
            },
        },
        methods: {
            performLogin () {
                let payload = { login: this.$data.login, password: this.$data.password },
                    redirect = this.$route.query.redirect;

                this.$store.dispatch('security/login', payload)
                    .then(data => {
                        if(this.error !== "error in login please try again" )
                        {
                            this.$awn.success("Login is success");
                            if (typeof redirect !== 'undefined') {
                                this.$router.push({path: redirect});
                            } else {
                                this.$router.push({path: '/'});
                            }
                        }
                        else {
                            this.$awn.alert("wrong username and password");
                        }
                    });
            },
        },
    }
</script>









