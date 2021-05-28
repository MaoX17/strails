<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">


        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="description" content="@yield('meta_description', 'Controlla e aggiorna lo stato dei sentieri')">
        <meta name="author" content="@yield('meta_author', 'Maurizio Proietti')">


        <meta property="og:url" content="{{url('/')}}" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{{app_name()}}" />
        <meta property="og:description" content="Controlla e aggiorna lo stato dei sentieri" />
        <meta property="og:image" content="{{ secure_asset('img/logo/logo16_t.png') }}" />

        <link rel="shortcut icon" type="image/png" href="{{ secure_asset('img/logo/logo16_t.png') }}"/>
        <link rel="apple-touch-icon" sizes="57x57" href="{{ secure_asset('img/logo/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ secure_asset('img/logo/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ secure_asset('img/logo/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ secure_asset('img/logo/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ secure_asset('img/logo/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ secure_asset('img/logo/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ secure_asset('img/logo/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ secure_asset('img/logo/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ secure_asset('img/logo/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ secure_asset('img/logo/apple-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ secure_asset('img/logo/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ secure_asset('img/logo/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ secure_asset('img/logo/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ secure_asset('img/logo/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ secure_asset('img/logo/ms-icon-144x144.png') }}">


    @yield('meta')


        <!-- Styles -->
        @yield('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">


        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                        'csrfToken' => csrf_token(),
                    ]); ?>
        </script>


        <script src='{{ secure_asset('js/geoPosition.js') }}' type="text/javascript" charset="utf-8"></script>


        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" crossorigin="" />

        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />

        <link href='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/leaflet.fullscreen.css' rel='stylesheet' />

<!--        <link href="{{ secure_asset('css/OverPassLayer.css') }}" rel='stylesheet'/> -->



        <!-- Make sure you put this AFTER Leaflets CSS -->
        <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" crossorigin=""></script>

        <script type='text/javascript' src="https://cdn.rawgit.com/makinacorpus/Leaflet.GeometryUtil/master/src/leaflet.geometryutil.js"></script>

        <script type='text/javascript' src="{{ secure_asset('js/leaflet-color-markers.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>

        <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster-src.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-ajax/2.1.0/leaflet.ajax.min.js"></script>
        <script src='https://api.mapbox.com/mapbox.js/plugins/leaflet-fullscreen/v1.0.1/Leaflet.fullscreen.min.js'></script>
<!--
        <script src="{{ secure_asset('js/OverPassLayer.js') }}"></script>
        <script src="{{ secure_asset('js/leaflet.active-layers.min.js') }}"></script>
        <script src="{{ secure_asset('js/leaflet.select-layers.min.js') }}"></script>
-->
        @yield('after-styles')


        <style>
            #map {
                height: 320px;
                z-index: 1;
                //height: 400px;
                //width: 100%;
                //height: calc(width * 1.75);
            }


            .legend {
                text-align:left;
                line-height: 18px;
                color: #555;
                padding: 6px 8px;
                font: 14px/16px Arial, Helvetica, sans-serif;
                background-color: white;
                background: rgba(255,255,255,0.8);
                box-shadow: 0 0 15px rgba(0,0,0,0.2);
                border-radius: 5px;
            }
        </style>

    </head>
    <body id="app-layout">
        <div id="app">

            @include('frontend.includes.nav')

            <div class="container">
                @include('includes.partials.messages')
                @yield('content')
            </div><!-- container -->
        </div><!--#app-->

        <!-- Scripts -->
        @yield('before-scripts')

        <!-- {!! Html::script(secure_asset('js/app.js')) !!} -->
        <script src="{{ secure_asset('js/manifest.js') }}" type="text/javascript" charset="utf-8"></script>
        <script src="{{ secure_asset('js/vendor.js') }}" type="text/javascript" charset="utf-8"></script>
        <script src="{{ secure_asset('js/app.js') }}" type="text/javascript" charset="utf-8"></script>



        @yield('after-scripts')

        @include('includes.partials.ga')





        <!-- Main Footer -->
<br>
<!-- Footer -->
<footer class="page-footer font-small blue">

  <!-- Copyright -->
  <div class="footer-copyright text-center py-3">
    <strong>Copyright &copy; {{ date('Y') }} <a href="#" title="Strails">{{ app_name() }}</a>.</strong> Tutti i diritti riservati
    - <a href="{{url('/privacy2')}}" target="_blank">Informativa privacy</a> -
    Autore: <a href="http://www.maurizio.proietti.name" rel="noreferrer" target="_blank" title="Strails Author">Maurizio Proietti</a>
  </div>
  <!-- Copyright -->

</footer>
<!-- Footer -->


</body>

</html>
