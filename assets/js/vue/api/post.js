import axios from 'axios';

export default {
    create (message) {
        return axios.post(
            '/api/post/create',
            {
                message: message,
            }
        );
    },
    edit (post) {
        return axios.post(
            '/api/post/edit',
            {
                post: post,
            }
        );
    },
    getAll () {
        return axios.get('/api/posts');
    },
    details (postId) {
        return axios.get('/api/post/details/' + postId);
    },
}