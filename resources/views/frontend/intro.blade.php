@extends('frontend.layouts.app')



@section('content')

    <div class="row text-center">
        <div class="col-md-2 col-md-offset-5 col-xs-4 col-xs-offset-4">
            <a href="{{ route('frontend.index_home') }}" class="thumbnail" title="Strails">
                <img src="{{ secure_asset('img/logo/logo230.png') }}" alt="Logo">
            </a>
        </div>
    </div>

    <div class="row text-center">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h1 class="panel-title">
                        {{ env('APP_NAME') }}
                </h1>
            </div>
            <div class="panel-body">
                <div class="row">
                    <p>
                        <b>Se in basso compare la scritta "Aggiungi Strails alla schermata di Home" Cliccala</b>
                        <br>
                      Poi segui i punti qui sotto:
                      <br>
                    </p>
                </div>
                <div class="row">
                <div class="col-md-4">

                    <a class="btn btn-info" href="{{ route('frontend.auth.login') }}" title="Strails Login">
                        <span class="badge">1</span>
                        Esegui il login
                    </a>
                </div>
                <div class="col-md-4">
                    <a class="btn btn-info" href="{{ route('frontend.index_home') }}" title="Strails Home">
                        <span class="badge">2</span>
                        Vai alla pagina principale
                    </a>
                </div>
                <div class="col-md-4">
                    <a class="btn btn-info" href="{{ route('frontend.guide') }}" title="Strails Guida">
                        <span class="badge">3</span>
                        Leggi la guida di Strails
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>


    @include('includes.partials.stat')
    @include('includes.partials.follow_us_button')
    @include('includes.partials.parlanodinoi')
    @include('includes.partials.credits')
    @include('includes.partials.app_description')


@endsection


