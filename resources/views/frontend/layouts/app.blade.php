<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <link rel="manifest" href="/manifest.json">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- webmaster tools bing -->
        <meta name="msvalidate.01" content="4A69E03A798F283D6F576A4027178DA1" />

        <title>@yield('title', app_name())</title>

        <!-- Meta -->

        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="author" content=" {{ env('APP_AUTHOR') }} ">

        <meta property="og:url" content="{{url('/')}}" />
        <meta property="og:type" content="website" />
        <meta property="og:title" content="{{app_name()}}" />
        <meta property="og:site_name" content="{{app_name()}}" />
        <meta property="og:description" content="{{ env('APP_DESCRIPTION') }}" />
        <meta property="og:image" content="{{ secure_asset('img/logo/logo468_t.png') }}" />

        <meta name="theme-color" content="#fff" />


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
        <meta name="msapplicati on-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ secure_asset('img/logo/ms-icon-144x144.png') }}">

        <meta property="fb:app_id" content="2017493218462196" />

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@strails" />
        <meta name="twitter:title" content="{{ app_name() }}" />
        <meta name="twitter:description" content="{{ env('APP_DESCRIPTION') }}" />
        <meta name="twitter:image" content="{{ secure_asset('img/logo/logo468_t.png') }}" />

        <link rel="canonical" href="{{url('/')}}"/>



        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
            <!-- {{ Html::style(secure_asset('css/app.css')) }} -->

        <!-- <link media="all" type="text/css" rel="stylesheet" href="{{secure_url(secure_asset('css/app.css'))}}'"> -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link href="{{ secure_asset('css/app.css') }}" rel="stylesheet">

        @yield('after-styles')

        <!-- Scripts -->
        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
            ]); ?>
        </script>

        <!-- <script src='{{ secure_asset('js/geoPosition.js') }}' type="text/javascript" charset="utf-8"></script> -->



        <!-- Scripts -->
        <script>
            window.Laravel = {!! json_encode([
                    'user' => Auth::user(),
                    'csrfToken' => csrf_token(),
                    'vapidPublicKey' => config('webpush.vapid.public_key'),
                    'pusher' => [
                        'key' => config('broadcasting.connections.pusher.key'),
                        'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                    ],
                ]) !!};
        </script>

        <script data-ad-client="ca-pub-8599396637502111" async
            src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>


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
<!--        {!! Html::script(secure_asset('js/app.js')) !!} -->
<!--        <script async src='{{secure_url(secure_asset('js/app.js'))}}' type="text/javascript" charset="utf-8"></script> -->

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
