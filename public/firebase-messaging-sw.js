importScripts('https://www.gstatic.com/firebasejs/10.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.0.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyC3aw-gErkZqQUKquBoCOI1z5rb3UjVxLQ",
    authDomain: "cpp-test-adc6d.firebaseapp.com",
    projectId: "cpp-test-adc6d",
    storageBucket: "cpp-test-adc6d.firebasestorage.app",
    messagingSenderId: "430198154493",
    appId: "1:430198154493:web:c9eeb523c7ff0c2b428a94",
    measurementId: "G-J91N4EV35L"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
  self.registration.showNotification(payload.notification.title, {
    body: payload.notification.body,
    data: payload.data,
  });
});
