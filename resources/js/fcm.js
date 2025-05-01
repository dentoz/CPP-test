import { messaging, getToken, onMessage } from './firebase';

getToken(messaging, { vapidKey: 'BFGUFtTocmXLsGiyGcIwY7a8cEBX5gx0LWrwzT4STbB9tvzDpVlJy9EKafpwmJt1dqmS9sT0zPjeB-01670bPR0' })
  .then((token) => {
    if (token) {
      axios.post('/api/fcm-token', { fcm_token: token });
    }
  });

onMessage(messaging, (payload) => {
  console.log('FCM Notification received: ', payload);
  // Tampilkan toast dengan Join/Reject jika ada invitation
});
