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
    apiKey: "AIzaSyDnsq9Xqk5aKopuR6RbFiBskefhffFtM-s",
    authDomain: "erpatsystem.firebaseapp.com",
    projectId: "erpatsystem",
    storageBucket: "erpatsystem.appspot.com",
    messagingSenderId: "1067070318526",
    appId: "1:1067070318526:web:4d461094f4c0320078bbef",
    measurementId: "G-V10T1ZH8WN"
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