
/*
function sendNotification() {
    var data = new FormData();
    //data.append('title', document.getElementById('title').value);
    //data.append('body', document.getElementById('body').value);
    console.log("aaaa");
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/ajax/send-notification/', true);
    xhr.onload = function () {
        // do something to response
        console.log(this.responseText);
    };
    xhr.send(data);
}
*/

var _registration = null;
async function registerServiceWorker() {
    try {
        const registration = await navigator.serviceWorker.register('/service-worker.js');
        console.log('Service worker successfully registered.');
        _registration = registration;
        return registration;
    }
    catch (err) {
        console.error('Unable to register service worker.', err);
    }
}
async function askPermission(vapid_key) {
    const permissionResult_1 = await new Promise(function (resolve, reject) {
        const permissionResult = Notification.requestPermission(function (result) {
            resolve(result);
        });
        if (permissionResult) {
            permissionResult.then(resolve, reject);
        }
    });
    if (permissionResult_1 !== 'granted') {
        throw new Error('We weren\'t granted permission.');
    }
    else {
        subscribeUserToPush(vapid_key);
    }
}
function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/\-/g, '+')
        .replace(/_/g, '/');
    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}
function getSWRegistration() {
    var promise = new Promise(function (resolve, reject) {
        // do a thing, possibly async, then…
        if (_registration != null) {
            resolve(_registration);
        }
        else {
            reject(Error("It broke"));
        }
    });
    return promise;
}
function subscribeUserToPush(vapid_key) {
    getSWRegistration()
        .then(function (registration) {
            //console.log(registration);
            //console.log("vapid: "+vapid_key );
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                   vapid_key
                    // "{{env('VAPID_PUBLIC_KEY')}}"
                
                )
            };
            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then(function (pushSubscription) {
            console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
            sendSubscriptionToBackEnd(pushSubscription);
            return pushSubscription;
        });
}
function sendSubscriptionToBackEnd(subscription) {

    //console.log("keys " + subscription);

    var json_data = JSON.stringify(subscription);
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: "/ajax/save-subscription",
        type: 'POST',
        dataType: 'json',
        data: {
            _token: CSRF_TOKEN,
            endpoint: subscription.endpoint,
            json_data: json_data

        },
        cache: false,
        success: function (data) {
            //console.log("pippo "+JSON.stringify(data));
            if ($.isEmptyObject(data.error)) {
                //console.debug(data);
                //console.debug(data.success);
                //console.log("Value added " + data.endpoint);

            } else {
                console.log(data.error);
            }
        }
    });

    //var data = JSON.stringify(subscription);
    //data.append('title', document.getElementById('title').value);
    //data.append('body', document.getElementById('body').value);
    //console.log("aaaa");

    /*
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/ajax/save-subscription', true);
    xhr.setRequestHeader("Content-Type", "application/json");    
    xhr.onload = function () {
        // do something to response
        //console.log(this.responseText);
    };
    xhr.send(data);

    return xhr.responseText
*/



    //return fetch('/api/save-subscription/{{Auth::user()->id}}', {
    //console.log("XXX : " + JSON.stringify(subscription));
    /*
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    console.log("token = "+CSRF_TOKEN);
    subscription['_token'] = CSRF_TOKEN;
    console.log("data: " + JSON.stringify(subscription));
    return fetch('/ajax/save-subscription', {
        credentials: 'include',
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(subscription)
    })
    
        .then(function (response) {
            console.log("response = " + JSON.stringify(response));
            if (!response.ok) {
                throw new Error('Bad status code from server.');
            }
            return response.json();
        })
        .then(function (responseData) {
            console.log("responseData:" + responseData);
            console.log("responseData:" + JSON.stringify(responseData));

            console.log("ok?: " + responseData.success);
            if (!(responseData && responseData.success)) {
                throw new Error('Bad response from server.');
            }
        });
        */
}
function enableNotifications(vapid_key) {
    //register service worker
    //check permission for notification/ask
    askPermission(vapid_key);
}


registerServiceWorker();
//    askPermission();
