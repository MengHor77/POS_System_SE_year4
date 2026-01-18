import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import '../css/app.css';

const appDiv = document.getElementById('app');

if (appDiv) {
    const csrf = appDiv.dataset.csrf;
    if (csrf) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
    }
}

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

createApp(App).use(router).mount('#app');
