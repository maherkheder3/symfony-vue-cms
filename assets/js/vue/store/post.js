import PostAPI from '../api/post';

export default {
    namespaced: true,
    state: {
        isLoading: false,
        error: null,
        posts: [],
        post:{} // must every things have a first value to save that
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
        hasPosts (state) {
            return state.posts.length > 0;
        },
        posts (state) {
            return state.posts;
        },
        details (state) {
            return state.post;
        },
    },
    mutations: {
        ['CREATING_POST'](state) {
            state.isLoading = true;
            state.error = null;
        },
        ['CREATING_POST_SUCCESS'](state, post) {
            state.isLoading = false;
            state.error = null;
            state.posts.unshift(post);
        },
        ['CREATING_POST_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.posts = [];
        },
        ['FETCHING_POSTS'](state) {
            state.isLoading = true;
            state.error = null;
            state.posts = [];
        },
        ['FETCHING_POSTS_SUCCESS'](state, posts) {
            state.isLoading = false;
            state.error = null;
            state.posts = posts;
        },
        ['FETCHING_POSTS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.posts = [];
        },


        ['GET_POST_DETAILS'](state) {
            state.isLoading = true;
            state.error = null;
            state.post = {};
        },
        ['POST_DETAILS_SUCCESS'](state, post) {
            state.isLoading = false;
            state.error = null;
            state.post = post;
        },
        ['POST_DETAILS_ERROR'](state, error) {
            state.isLoading = false;
            state.error = error;
            state.post = {};
        },
    },
    actions: {
        createPost ({commit}, message) {
            commit('CREATING_POST');
            return PostAPI.create(message)
                .then(res => commit('CREATING_POST_SUCCESS', res.data))
                .catch(err => commit('CREATING_POST_ERROR', err));
        },
        fetchPosts ({commit}) {
            commit('FETCHING_POSTS');
            return PostAPI.getAll()
                .then(res => commit('FETCHING_POSTS_SUCCESS', res.data))
                .catch(err => commit('FETCHING_POSTS_ERROR', err));
        },
        details ({commit}, postId) {
            commit('POST_DETAILS_SUCCESS');
            return PostAPI.details(postId)
                .then(res => {
                    commit('POST_DETAILS_SUCCESS', res.data)
                })
                .catch(err => commit('POST_DETAILS_ERROR', err));
        }
    },
}