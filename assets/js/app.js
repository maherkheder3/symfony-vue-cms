// loads the Bootstrap jQuery plugins
// import 'bootstrap-sass/assets/javascripts/bootstrap/carousel.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/transition.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/alert.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/collapse.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/dropdown.js';
// import 'bootstrap-sass/assets/javascripts/bootstrap/modal.js';

// loads the code syntax highlighting library
// import './highlight.js';

// Creates links to the Symfony documentation
// import './doclinks.js';

import 'babel-polyfill' // for IE 11



// vue loader
import Vue from 'vue';
import router from './vue/router/index';
import App from './vue/App';
import store from './vue/store'; // -> index.js


new Vue({
    template: '<App/>',
    components: { App },
    router,
    store,
}).$mount('#app');

