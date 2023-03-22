import './chat.css';
import { createApp } from 'vue';
import Chat from './chat.vue';

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const robochatDiv = document.createElement('div');
robochatDiv.id = 'robochat';
document.body.prepend(robochatDiv);

createApp(Chat).mount("#robochat");
