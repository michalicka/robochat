import './chat.css';
import { createApp } from 'vue';
import Chat from './chat.vue';

const robochatDiv = document.createElement('div');
robochatDiv.id = 'robochat';
document.body.prepend(robochatDiv);

createApp(Chat).mount("#robochat");
