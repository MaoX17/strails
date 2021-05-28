@extends('frontend.layouts.app_map_leaflet')

@section('content')

         <h1>Strails - Social Trails</h1>
         

         <div class="row">
             <div class="col-md-12">
                <a class="btn btn-success" href="{{$url_strava}}">Procedi e connetti a strava</a>
             </div>
         </div>



@endsection

@section('after-scripts')

@endsection