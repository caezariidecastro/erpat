importScripts('https://www.gstatic.com/firebasejs/8.4.3/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.4.3/firebase-messaging.js');

var firebaseConfig = {
    apiKey: "AIzaSyDBy1btu2_RUN2r4tWTtUY2Zhw4oJsum6E",
    authDomain: "businext-app.firebaseapp.com",
    projectId: "businext-app",
    storageBucket: "businext-app.appspot.com",
    messagingSenderId: "71633264934",
    appId: "1:71633264934:web:97e024337f1a70b4e6e1e7",
    measurementId: "G-X4D1D5TXDX"
};
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
const title = payload.data.title;
const option = {
    body: payload.data.body,
    icon: payload.data.icon,
    tag: 'firebase-messaging',
    data: {
        url: payload.data.url,
        extra: payload.data.extra
    }
}
return self.registration.showNotification(title, option);
})