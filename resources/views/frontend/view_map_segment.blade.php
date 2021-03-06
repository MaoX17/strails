@extends('frontend.layouts.app_map_leaflet')
@section('content')

<div class="row">
    <div class="col-md-12">
        <div id="map"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <h4>
            {{ $segment->echoNomeCorretto($segment->getName()) }}
            <a href="{{ route('viewSegmentMap', ['segment_id' => $segment->id]) }} " class="btn btn-sm btn-{{$segment->lastStateClass()}}" aria-label="Left Align">
                <i class="fas fa-map-marker-alt"></i>
                    </a>
            <small>{{ $segment->id }}</small>
    @include('includes.partials.share_button')


        </h4>

        @if ($logged_in_user)


        <br>
        <a class="btn btn-primary" href="{{ route('eval.new', ['evaluable_type' => 'StravaSegment', 'evaluable_id' => $segment->id]) }} ">Aggiungi una valutazione</a>
        @if(\Auth::user()->stravafavourites->contains($segment->id))
            <a href="{{ route('removeFavourite', ['rel_id' => $segment->id, 'rel_type' => 'StravaSegment']) }}" class="btn btn-success">
                <i class="fas fa-star"></i>
                Rimuovi dai preferiti
            </a>
        @else
            <a href="{{ route('addFavourite', ['rel_id' => $segment->id, 'rel_type' => 'StravaSegment']) }}" class="btn btn-primary">
                <i class="far fa-star"></i>
                Aggiungi ai preferiti
            </a>
        @endif

        <a href="{{route('index_home')}}" class="btn btn-info">Torna alla home</a>
        <a href="{{session('url_provenienza')}}" class="btn btn-info">Torna alla ultima attività strava visualizzata</a>

        <br><br> Valutazioni:
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Direzione</th>
                    <th>Attivit&agrave;</th>
                    <th>Descrizione</th>
                    <th>Utente</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($segment->evaluations as $eval)
                <tr class="{{$eval->rating}}">
                    <td> {{ $eval->updated_at->format('d/m/Y') }}</td>
                    <td>{{ $eval->direction }}</td>
                    <td>{{ $eval->sport }}</td>
                    <td>{{ $eval->rating_desc}}</td>
                    <td>
                        {{ $eval->author->name}}
                        <span class="badge"> {{$eval->author->evaluations()->count()}} </span>
                    </td>

                    <td>
                        {!! (($eval->author->id == \Auth::user()->id) ? ('
                        <a class="btn btn-sm btn-info" href="'.action('EvaluationController@edit',['evaluation_id' => $eval->id]).'"> <i class="fas fa-edit"></i> </a>')
                        : ('
                        <i class="fa fa-ban"></i>')) !!} {!! (($eval->author->id == \Auth::user()->id) ? ('
                        <a class="btn btn-sm btn-info" href="'.action('EvaluationController@delete',['evaluation_id' => $eval->id]).'"> <i class="fas fa-trash"></i> </a>')
                        : ('
                        <i class="fas fa-ban"></i>')) !!}
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>

        @elseif (! $logged_in_user) Effettua il {{ link_to_route('auth.login', 'Login', [], ['class'
        => active_class(Active::checkRoute('auth.login'))]) }} per leggere e aggiungere le valutazioni di questo
        sentiero. @endif

    </div>
</div>







<input id="segment" type="hidden" value="{{ $segment->id }}">
<input id="lat" type="hidden" value="{{ $first_node[1] }}">
<input id="lon" type="hidden" value="{{ $first_node[0] }}">
@endsection

@section('after-scripts')
<script async src="{{secure_asset('js/ajax_segment_visible_leaf.js')}}"></script>
@endsection
