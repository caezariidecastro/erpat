<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-analytics.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.4.3/firebase-messaging.js"></script>

<script>
  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  var firebaseConfig = {
    apiKey: "AIzaSyDBy1btu2_RUN2r4tWTtUY2Zhw4oJsum6E",
    authDomain: "businext-app.firebaseapp.com",
    projectId: "businext-app",
    storageBucket: "businext-app.appspot.com",
    messagingSenderId: "71633264934",
    appId: "1:71633264934:web:97e024337f1a70b4e6e1e7",
    measurementId: "G-X4D1D5TXDX"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();

  <?php if(get_setting('enable_firebase_integration')) { ?>
    const messaging = firebase.messaging();
    messaging.requestPermission()
    .then(async function() {
        return messaging.getToken();
    })
    .then(function(token) {
        console.log(token)
    })
    .catch(function(err) {
        console.log(err);
    });

    messaging.onMessage(function(payload) {
        console.log(payload);
        //appAlert.success('Hello');
    })
  <?php } ?>

</script>