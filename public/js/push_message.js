self.addEventListener('push', function (event) {
    console.info('Event: Push');
    if (event.data) {
        var data = event.data.json();
        
        event.waitUntil(

            self.registration.showNotification(data.title, {
                body: data.body,
                tag: data.tag,
                icon: data.icon,

                actions: data.actions,
                data: data.data
            })
        );
        console.log('This push event has data: ', event.data.text());
        console.log('This push event2: ', JSON.stringify(event));
    } else {
        console.log('This push event has no data.');
    }
});


self.addEventListener('notificationclick', function (event) {
    //console.log('event.action: ' + event.action);
    //console.log('Notification click: tag ', event.notification.tag);
    //console.log('[Service Worker] Notification click Received. event', event);

    //var url = 'https://google.com';
    var url = event.notification.data.url;
    console.log('cliccato su ' + url);

    event.notification.close();

/*
    if (event.action === 'like') {
        silentlyLikeItem();
    }
    else if (event.action === 'visualizza_sentiero') {
        clients.openWindow(event.notification.data.url);
    }
    else {
        clients.openWindow("https://www.google.it");
    }
*/

    
    event.waitUntil(
        clients.openWindow(url)
//        clients.matchAll({
//            type: 'window'
//        })
//            .then(function (windowClients) {
//                for (var i = 0; i < windowClients.length; i++) {
//                    var client = windowClients[i];
//                    if (client.url === url && 'focus' in client) {
//                        return client.focus();
//                    }
//                }
                
//                if (clients.openWindow && event.notification.data.url) {
                    //return clients.openWindow(event.notification.data.url);
//                }
//            })
//    );

    );
});