import { initializeApp } from 'firebase/app';
import { getAnalytics, isSupported } from 'firebase/analytics';
import { getAuth } from 'firebase/auth';
import { getFirestore } from 'firebase/firestore';

const firebaseConfig = {
    apiKey: 'AIzaSyC-kgalFn7u_ddduqY421zV-JJfwlvJi30',
    authDomain: 'smarthog-f2a97.firebaseapp.com',
    projectId: 'smarthog-f2a97',
    storageBucket: 'smarthog-f2a97.firebasestorage.app',
    messagingSenderId: '21218195063',
    appId: '1:21218195063:web:e8d21c60de323133a9f872',
    measurementId: 'G-079M3QW2MR',
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app);

if (typeof window !== 'undefined') {
    isSupported()
        .then((supported) => {
            if (supported) {
                getAnalytics(app);
            }
        })
        .catch(() => {
            // Analytics is optional for this app flow.
        });
}

export { app, auth, db };
