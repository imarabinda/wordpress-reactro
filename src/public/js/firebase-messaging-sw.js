// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.0.1/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.0.1/firebase-messaging.js');

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object

firebase.initializeApp({
  'messagingSenderId': location.search.split('messagingSenderId=')[1],
  'appId':location.search.split('appId=')[1],
  'apiKey':location.search.split('apiKey=')[1],
  'projectId':location.search.split('projectId=')[1]
});


// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

self.addEventListener('push', function(event) {
        const payload =JSON.parse(event.data.text());
console.log(payload);
        const title = payload.notification.title;
        const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,  
                image:payload.notification.image,
                tag:payload.notification.tag,
                requireInteraction:payload.data.require_interaction,
                renotify:payload.data.renotify,
                click_action:payload.data.click_action_Web,
            };

  self.registration.showNotification(title,
    options);
   
})


self.addEventListener('notificationclick', function(event) {
  event.notification.close();
  if (event.action === 'archive') {
    // Archive action was clicked
    campaighn_status(event);
  } else {
    // Main body of notification was clicked
    clients.openWindow('/inbox');
  }
}, false);


function campaighn_status(notification_id) {
            return $.ajax({
                url: that.settings.ajax_url,
                type: 'post',
                dataType: 'JSON',
                data: {
                    action: 'update_notification_clicked',
                    multicast_id: notification_id,
                },
            });
        }


