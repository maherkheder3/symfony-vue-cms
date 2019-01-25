import axios from 'axios';

export default {
    login (login, password, csrf_token) {
        return axios.post(
            '/api/security/login',
            {
                _username: login,
                _password: password,
                _csrf_token: csrf_token,
                _target_path: ""
            }
        );
    },
    logout(){
        return axios.post('/api/security/logout');
    }
}