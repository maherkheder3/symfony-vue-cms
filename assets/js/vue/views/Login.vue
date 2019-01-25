<template>
    <div>
        <v-layout>
            <v-flex>
                <v-card style="padding:60px">
                    <form>
                        <v-text-field
                                v-model="login"
                                :counter="3"
                                :rules="nameRules"
                                label="Name"
                                required
                                id="name"
                        ></v-text-field>

                        <v-text-field
                                v-model="password"
                                :counter="3"
                                label="Password"
                                required
                                :type="'password'"
                                id="password"
                        ></v-text-field>

                        <v-btn color="warning" @click="performLogin()" :disabled="login.length === 0 || password.length === 0 || isLoading">Login</v-btn>
                    </form>
                </v-card>
            </v-flex>
        </v-layout>

        <div v-if="isLoading">
            <loading></loading>
        </div>

        <div v-else-if="hasError" class="row col">
            <div class="alert alert-danger" role="alert">
                {{ error }}
            </div>
        </div>
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
                            if (typeof redirect !== 'undefined') {
                                this.$router.push({path: redirect});
                            } else {
                                this.$router.push({path: '/'});
                            }
                        }
                    });
            },
        },
    }
</script>