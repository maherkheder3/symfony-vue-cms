import axios from 'axios';

export default {
    login (login, password) {
        return axios.post(
            '/api/security/logintwo',
            {
                username: login,
                password: password
            }
        );
    },
}