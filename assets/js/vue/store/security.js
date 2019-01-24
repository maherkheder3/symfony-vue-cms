import SecurityAPI from '../api/security';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        isAuthenticated: false,
        roles: [],
        csrf_token: "",
    },
    getters: {
        isLoading (state) {
            return state.isLoading;
        },
        hasError (state) {
            return state.error !== null;
        },
        error (state) {
            return state.error;
        },
        isAuthenticated (state) {
            return state.isAuthenticated;
        },
        hasRole (state) {
            return role => {
                return state.roles.indexOf(role) !== -1;
            }
        },
        csrf_token (state){
            return state.csrf_token;
        }
    },
    mutations: {
        ['AUTHENTICATING'](state) {
            state.isLoading = true;
            state.error = null;
            state.isAuthenticated = false;
            state.roles = [];
        },
        ['AUTHENTICATING_SUCCESS'](state, roles) {
            state.isLoading = false;
            state.error = null;
            state.isAuthenticated = true;
            state.roles = roles;
        },
        ['AUTHENTICATING_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.isAuthenticated = false

            state.roles = [];
        },
        ['PROVIDING_DATA_ON_REFRESH_SUCCESS'](state, payload) {
            state.isLoading = false;
            state.error = null;
            state.isAuthenticated = payload.isAuthenticated;
            state.roles = payload.roles;
        },
    },
    actions: {
        login ({commit}, payload) {
            commit('AUTHENTICATING');
            return SecurityAPI.login(payload.login, payload.password, this.state.csrf_token)
                .then(res => commit('AUTHENTICATING_SUCCESS', res.data))
                .catch(err => commit('AUTHENTICATING_ERROR', err));
        },
        onRefresh({commit}, payload) {
            commit('PROVIDING_DATA_ON_REFRESH_SUCCESS', payload);
            this.state.csrf_token = payload.csrf_token.value;
        },
    },
}