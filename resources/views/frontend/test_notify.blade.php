@extends('frontend.layouts.app')

@section('content')

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">Dashboard</div>
            <div class="panel-body">
                <button class="btn btn-info" id="enable-notifications" onclick="enableNotifications()"> Enable Push Notifications </button>
                <div class="form-group">
                    <input class="form-control" id="title" placeholder="Notification Title">
                </div>
                <div class="form-group">
                    <textarea id="body" class="form-control" placeholder="Notification body"></textarea>
                </div>
                <div class="form-group">
                    <button class="btn" onclick="sendNotification()">Send Notification</button>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    function sendNotification(){
        var data = new FormData();
        data.append('title', document.getElementById('title').value);
        data.append('body', document.getElementById('body').value);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', "{{url('/api/send-notification/'.auth()->user()->id)}}", true);
        xhr.onload = function () {
            // do something to response
            console.log(this.responseText);
        };
        xhr.send(data);
    }
    var _registration = null;
    function registerServiceWorker() {
        return navigator.serviceWorker.register('/service-worker.js')
        .then(function(registration) {
            console.log('Service worker successfully registered.');
            _registration = registration;
            return registration;
        })
        .catch(function(err) {
            console.error('Unable to register service worker.', err);
        });
    }
    function askPermission() {
        return new Promise(function(resolve, reject) {
            const permissionResult = Notification.requestPermission(function(result) {
                resolve(result);
            });
            if (permissionResult) {
                permissionResult.then(resolve, reject);
            }
        })
        .then(function(permissionResult) {
            if (permissionResult !== 'granted') {
                throw new Error('We weren\'t granted permission.');
            }
            else{
                subscribeUserToPush();
            }
        });
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
    function getSWRegistration(){
        var promise = new Promise(function(resolve, reject) {
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
    function subscribeUserToPush() {
        getSWRegistration()
        .then(function(registration) {
            console.log(registration);
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                "{{env('VAPID_PUBLIC_KEY')}}"
                )
            };
            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then(function(pushSubscription) {
            console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
            sendSubscriptionToBackEnd(pushSubscription);
            return pushSubscription;
        });
    }
    function sendSubscriptionToBackEnd(subscription) {
        return fetch('/api/save-subscription/{{Auth::user()->id}}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(subscription)
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Bad status code from server.');
            }
            return response.json();
        })
        .then(function(responseData) {
            console.log("responseData:"+JSON.stringify(responseData));

            console.log("ok?: "+responseData.success);
            if (!(responseData && responseData.success)) {
                throw new Error('Bad response from server.');
            }
        });
    }
    function enableNotifications(){
        //register service worker
        //check permission for notification/ask
        askPermission();
    }
    registerServiceWorker();

</script>
@endsection
