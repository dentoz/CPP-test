import { initializeApp } from 'firebase/app';
import { getMessaging, getToken, onMessage } from 'firebase/messaging';

const firebaseConfig = {
    apiKey: "AIzaSyC3aw-gErkZqQUKquBoCOI1z5rb3UjVxLQ",
    authDomain: "cpp-test-adc6d.firebaseapp.com",
    projectId: "cpp-test-adc6d",
    storageBucket: "cpp-test-adc6d.firebasestorage.app",
    messagingSenderId: "430198154493",
    appId: "1:430198154493:web:c9eeb523c7ff0c2b428a94",
    measurementId: "G-J91N4EV35L"
}

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

export { messaging, getToken, onMessage };
