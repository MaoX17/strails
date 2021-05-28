@extends('frontend.layouts.app')


@section('after-scripts')

<!-- <script async src='{{secure_asset('js/notifiche.js')}}' type="text/javascript" charset="utf-8"></script> -->
<script async src='{{secure_url('js/notifiche.js')}}' type="text/javascript" charset="utf-8"></script>

<script>
    if ('serviceWorker' in navigator && 'PushManager' in window) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/service-worker.js').then(function(registration) {
                    registration.update();
                //navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    // Registration was successful
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);

                    if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
                        console.warn('Notifications aren\'t supported.');
                        return;
                    }
                    // Check the current Notification permission.
                    // If its denied, it's a permanent block until the
                    // user changes the permission
                    if (Notification.permission === 'denied') {
                        console.warn('The user has blocked notifications.');
                        return;
                    }

                    // Check if push messaging is supported
                    if (!('PushManager' in window)) {
                        console.warn('Push messaging isn\'t supported.');
                        return;
                    }

                    // We need the service worker registration to check for a subscription
                    navigator.serviceWorker.ready.then(function(serviceWorkerRegistration) {
                        // Do we already have a push message subscription?
                        serviceWorkerRegistration.pushManager.getSubscription()
                        .then(function(subscription) {
                            // Enable any UI which subscribes / unsubscribes from
                            // push messages.
                        @if ($logged_in_user)
                            if (!subscription) {
                            // We aren't subscribed to push, so set UI
                            // to allow the user to enable push
                            enableNotifications("{{ env('VAPID_PUBLIC_KEY')}}");
                            return;
                            }

                            // Keep your server in sync with the latest subscriptionId
                            sendSubscriptionToBackEnd(subscription);

                            // Set your UI to show they have subscribed for
                            // push messages

                            isPushEnabled = true;

                        @endif
                        })
                        .catch(function(err) {
                            console.warn('Error during getSubscription()', err);
                        });
                    });



                    }, function(err) {
                    // registration failed :(
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

</script>



@endsection


@section('content')

    <div class="row">
        <div class="col-lg-2 offset-md-5 col-4 offset-4">
            <a href="{{ route('index_home') }}" class="thumbnail" title="Strails">
                <img class="img-thumbnail" src="{{ secure_asset('img/logo/logo468_t.png') }}" alt="Logo">
            </a>
        </div>
    </div>

    <br>

    <div class="row">
        <div class="col-md-12 centered">
            <a href="{{ route('mappa') }}" class="form-control btn btn-primary" title="Strails mappa">Apri e scorri la mappa...</a>
        </div>
    </div>

   <br>

    <div class="row">

    @if (!$logged_in_user)
        <div class="col-md-3 col-md-offset-3 col-xs-10 col-xs-offset-1 centered">
            <a class="btn btn-info" href="{{ route('auth.login') }}" title="Esegui login">Esegui il Login</a>
        </div>


    @elseif ((isset($logged_in_user->token_strava_access)) && (isset($logged_in_user->token_strava_refresh)))

        <div class="col-md-12">
            @include('includes.partials.strava_activities')
        </div>
    @else
        <div class="col-md-3 col-xs-10 col-xs-offset-1 centered">
            <a href="{{ route('strava.getauthorization') }}" title="E' necessario aver fatto login">
                <img src="{{secure_asset('img/btn_strava_connectwith_light.png')}}" alt="Strava Logo">
            </a>
        </div>
    @endif

    </div>

    <hr>


    @if ($logged_in_user)

        @if (isset($logged_in_user->token_strava_access))

        <div class="card text-center">
            <div class="card-header">
                <h4>Segmenti Strava preferiti:</h4>
            </div>
            <div class="card-body">
                <div class="row">
                @foreach(\Auth::user()->stravafavourites as $rel)

                <div class="col-xs-12 col-md-4 text-center">
                    <a href="{{route('viewSegmentMap', ['segment_id' => $rel->id]) }}" class="btn btn-sm btn-outline-{{$rel->lastStateClass()}}" aria-label="Left Align" title="Strails segmenti strava preferiti">
                        <i class="fas fa-map-marker-alt"></i>
                            {{ $rel->echoNomeCorretto($rel->getName()) }}
                        </a>

                    <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
                </div>

                @endforeach
                </div>
            </div>
          </div>




        <div class="row">
            <h4>Segmenti Strava preferiti:</h4>

            @foreach(\Auth::user()->stravafavourites as $rel)

            <div class="col-xs-12 col-md-4 text-center">
                <a href="{{route('viewSegmentMap', ['segment_id' => $rel->id]) }}" class="btn btn-sm btn-{{$rel->lastStateClass()}}" aria-label="Left Align" title="Strails segmenti strava preferiti">
                    <i class="fas fa-map-marker-alt"></i>
                        {{ $rel->echoNomeCorretto($rel->getName()) }}
                    </a>

                <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
            </div>

            @endforeach

        </div>

<!-------------------------------------->


            @if (is_array($starred) && count($starred) > 0)
            <div class="card text-center">
                <div class="card-header">
                    <h4>Segmenti preferiti presi da Strava:</h4>
                </div>
                <div class="card-body">
                    @foreach ($starred as $item)
                    <div class="col-xs-12 col-md-4 text-center">
                        <a href="{{route('viewSegmentMap', ['segment_id' => $item->id]) }}" class="btn btn-sm btn-info" aria-label="Left Align"
                            title="Strails segmenti strava preferiti da Strava">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $item->name }}
                        </a>
                    </div>
                @endforeach

                </div>
            </div>
            @endif




            @if (is_array($starred) && count($starred) > 0)
            <div class="row">
                <h4>Segmenti preferiti presi da Strava:</h4>
                @foreach ($starred as $item)
                    <div class="col-xs-12 col-md-4 text-center">
                        <a href="{{route('viewSegmentMap', ['segment_id' => $item->id]) }}" class="btn btn-sm btn-info" aria-label="Left Align"
                            title="Strails segmenti strava preferiti da Strava">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $item->name }}
                        </a>
                    </div>
                @endforeach
            </div>
            @endif

        @endif



        <div class="card text-center">
            <div class="card-header">
                <h4>Sentieri preferiti:</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(\Auth::user()->favourites as $rel)
                    <div class="col-xs-12 col-md-4 text-center">
                        <a href="{{route('viewRelationMap', ['relid' => $rel->id]) }}" class="btn btn-sm btn-outline-{{$rel->lastStateClass()}}" aria-label="Left Align" title="Strails sentieri preferiti">
                            <i class="fas fa-map-marker-alt"></i>
                            {{ $rel->echoNomeCorretto($rel->getName()) }}
                        </a>

                        <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
                    </div>
                @endforeach
            </div>

            </div>
        </div>

    <div class="row">

        <h4>Sentieri preferiti:</h4>

        @foreach(\Auth::user()->favourites as $rel)
        <div class="col-xs-12 col-md-4 text-center">
            <a href="{{route('viewRelationMap', ['relid' => $rel->id]) }}" class="btn btn-sm btn-{{$rel->lastStateClass()}}" aria-label="Left Align" title="Strails sentieri preferiti">
                <i class="fas fa-map-marker-alt"></i>
                {{ $rel->echoNomeCorretto($rel->getName()) }}
            </a>

            <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
        </div>

        @endforeach



    </div>

    <div class="row">
        <div class="col-md-12">
            @include('includes.partials.mine_evaluations')
        </div>
    </div>

    @endif



    <div class="row">
        <div class="col-md-12">
            @include('includes.partials.evaluations')
        </div>
    </div>


    <div id="ads" class="row">
        <div class="col-md-12">

        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
    @include('includes.partials.best_users')
        </div>
    </div>

    @include('includes.partials.stat')
    @include('includes.partials.follow_us_button')
    @include('includes.partials.parlanodinoi')
    @include('includes.partials.credits')
    @include('includes.partials.app_description')


@endsection



@section('after-scripts')

@include('includes.partials.structured_data')
@if ($logged_in_user)
<script>
   // enableNotifications();
</script>
@endif

@endsection
