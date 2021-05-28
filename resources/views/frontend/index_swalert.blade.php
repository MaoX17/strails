@extends('frontend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-2 col-md-offset-5 col-xs-4 col-xs-offset-4">
            <a href="{{ route('frontend.index') }}" class="thumbnail" title="Strails">
                <img src="{{ secure_asset('img/logo/logo468_t.png') }}" alt="Logo">
            </a>
        </div>
    </div>


    <div class="row">
        <div class="col-md-10 col-md-offset-1 col-xs-10 col-xs-offset-1 centered">
            <a href="{{ route('mappa') }}" class="form-control btn btn-primary" title="Strails mappa">Apri e scorri la mappa...</a>
        </div>
    </div>

   <br>

    <div class="row">

            @if (isset($logged_in_user->token_strava_access))
            <div class="col-md-12">
                @include('includes.partials.strava_activities')
            </div>
            @else
            <div class="col-md-3 col-md-offset-3 col-xs-10 col-xs-offset-1 centered">
                <a href="{{ route('strava.getauthorization') }}" title="E' necessario aver fatto login">
                    <img src="{{secure_asset('img/btn_strava_connectwith_light.png')}}" alt="Strava Logo">
                </a>
            </div>
            @endif
            @if (!$logged_in_user)
            <div class="col-md-3 col-xs-10 col-xs-offset-1 centered">
                <a class="btn btn-info" href="{{ route('frontend.auth.login') }}" title="Esegui login">Esegui il Login</a>
            </div>
            @endif
            <hr>

    </div>



    @if ($logged_in_user)
        @if (isset($logged_in_user->token_strava_access))

        <div class="row">
            <h4>Segmenti Strava preferiti:</h4>

            @foreach(\Auth::user()->stravafavourites as $rel)

            <div class="col-xs-12 col-md-4 text-center">
                <a href="{{route('viewSegmentMap', ['relid' => $rel->id]) }}" class="btn btn-sm btn-{{$rel->lastStateClass()}}" aria-label="Left Align" title="Strails segmenti strava preferiti">
                        <span class="glyphicon glyphicon glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                        {{ $rel->echoNomeCorretto($rel->getName()) }}
                    </a>

                <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
            </div>

            @endforeach

        </div>

        @endif


    <div class="row">

        <h4>Sentieri preferiti:</h4>

        @foreach(\Auth::user()->favourites as $rel)

        <div class="col-xs-12 col-md-4 text-center">
            <a href="{{route('viewRelationMap', ['relid' => $rel->id]) }}" class="btn btn-sm btn-{{$rel->lastStateClass()}}" aria-label="Left Align" title="Strails sentieri preferiti">
                <span class="glyphicon glyphicon glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                {{ $rel->echoNomeCorretto($rel->getName()) }}
            </a>

            <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
        </div>

        @endforeach



    </div>

    @endif

    <div class="row">
        <div class="col-md-12">
            @include('includes.partials.evaluations')
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

@if (! $logged_in_user)

<script type="text/javascript" src="//wurfl.io/wurfl.js"></script>

<script>
$(document).ready(function () {
    if(WURFL.is_mobile) { //dostuff(); }
        var linkURL = '/intro';
        const isInStandaloneMode = () => (window.matchMedia('(display-mode: standalone)').matches) || (window.navigator.standalone);
        if (isInStandaloneMode())
        {
            console.log("webapp is installed")
        }
        else {
            alertRedirect(linkURL);
        }

    }
  });


  function alertRedirect(linkURL)
  {

    swal({
        title: "Vuoi trasformare Strails il una App?",
        text: "Verrai rediretto alla guida da seguire - Se esegui il login non vedrai più questo avviso",
        type: "info",
        timer: 7000,
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Si! Voglio una App",
        cancelButtonText: "No, grazie",
        closeOnConfirm: false,
        closeOnCancel: false
        },
        function(isConfirm) {
        if (isConfirm) {
            window.location.href = linkURL;
        } else {
            swal("Grazie.", "Ricorda che per non vedere questo avviso basta eseguire il login.", "info");
        }
    });
  }


</script>

@endif

@endsection
