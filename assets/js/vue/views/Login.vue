<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-3-offset"></div>
            <div class="col-md-6">
                <div class="well">
                    <form method="post">
                        <fieldset>
                            <legend><i class="fa fa-lock" aria-hidden="true"></i>Login</legend>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input v-model="login" type="text" class="form-control" id="username" name="_username">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input v-model="password" type="password" class="form-control" id="password" name="_password">
                            </div>
                            <!--<input type="hidden" name="_target_path" value="{{ app.request.get('redirect_to') }}"/>-->
                            <!--<input type="hidden" name="_csrf_token" v-model="csrf_token"/>-->
                            <!--<button type="submit" class="btn btn-primary">-->
                                <!--<i class="fa fa-sign-in" aria-hidden="true"></i> {{ 'action.sign_in'|trans }}-->
                            <!--</button>-->
                            <button @click="performLogin()" :disabled="login.length === 0 || password.length === 0 ||
                            isLoading" type="button" class="btn btn-primary">Login</button>

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="row col">
            <p>Loading...</p>
        </div>

        <div v-else-if="hasError" class="row col">
            <div class="alert alert-danger" role="alert">
                {{ error }}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'login',
        data () {
            return {
                login: '',
                password: '',
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
                    .then(() => {
                        if (typeof redirect !== 'undefined') {
                            this.$router.push({path: redirect});
                        } else {
                            this.$router.push({path: '/'});
                        }
                    });
            },
        },
    }
</script>