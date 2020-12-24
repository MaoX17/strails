@extends('frontend.layouts.app_map_leaflet')

@section('content')

         <p class="lead">Le tue attività su Strava:</p>

        <div class="row">
            <div class="col-md-12">
            <ul>
            @foreach ($activities as $activity)
            <li>
                @isset($activity->map->summary_polyline)
                <a class="btn btn-sm btn-info" href="{{ route('strava.stravaNearTrails', ['activity_id' => $activity->id ]) }}">
                @endisset

                {{ (new \DateTime($activity->start_date))->format('d/m/Y') }} - {{$activity->name}} - {{$activity->type}} 

                
                    <input class="bottone" type='hidden' id='{{$activity->id}}' value='{{$activity->map->summary_polyline}}'> 
                
                @isset($activity->map->summary_polyline)
                </a>
                @endisset
               </li>
            @endforeach
            </ul>
            

            </div>
        </div>


@endsection
