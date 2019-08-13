self.addEventListener('push', function (/** PushEvent */ event) {
    if (!(self.Notification && self.Notification.permission === 'granted')) {
        return;
    }

    var showNotification = function (notification) {
        return self.registration.showNotification(notification.title, {
            body: notification.body,
            icon: notification.icon || notification.image,
            image: notification.image,
            data: notification
        });
    };

    if (event.data) {
        var json = event.data.json();

        if (json) {
            event.waitUntil(showNotification(json));
        } else {
            console.warn("Invalid push data received", event);
        }
    } else {
        console.warn("Empty push received", event);
    }
});

self.addEventListener('notificationclick', function (/** NotificationEvent */event) {
    var notification = event.notification;

    // Cerrar notificaciÃ³n
    notification.close();

    // URL que se quiere abrir
    var urlToOpen = new URL((notification.data && notification.data.url) || '/', self.location.origin).href;

    event.waitUntil(clients.matchAll({
        type: 'window',
        includeUncontrolled: true
    }).then(function (clientList) {
        for (var i = 0; i < clientList.length; i++) {
            var client = clientList[i];
            if (client.url === urlToOpen && 'focus' in client) { // Focalizar pestaÃ±a existente
                return client.focus();
            }
        }

        if (clients.openWindow) { // Abrir nueva pestaÃ±a
            return clients.openWindow(urlToOpen);
        }
    }));
});