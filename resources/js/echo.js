import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// const userData = JSON.parse(localStorage.getItem("token") ?? "{}");
// const csrfToken = document.cookie
// .split('; ')
// .find(row => row.startsWith('XSRF-TOKEN'))
// ?.split('=')[1];

export const echo = new Echo({
  broadcaster: 'pusher',
  key: '746d9aa45990c6441201',
  cluster: 'mt1',
  wsHost: window.location.hostname,
  wsPort: 6001,
  forceTLS: false,
  disableStats: true,
  enabledTransports: ['ws', 'wss'],
});
