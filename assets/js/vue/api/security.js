import axios from 'axios';

export default {
    login (login, password, csrf_token) {
        return axios.post(
            '/api/security/login',
            {
                username: login,
                password: password,
                csrf_token: csrf_token
            }
        );
    },
}