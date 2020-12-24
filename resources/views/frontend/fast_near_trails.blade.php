@extends('frontend.layouts.app_map_leaflet')

@section('after-styles')
    <style>

        #map { height: 300px; }

    </style>
@endsection

@section('content')



         <div class="row">
             <div class="col-md-12">

                 @foreach($near_rels_array as $rel)
                    <div>
                        <a href="{{route('viewRelationMap', ['relid' => $rel->id]) }}" class="btn btn-sm btn-{{$rel->lastStateClass()}}" aria-label="Left Align">
                          <span class="glyphicon glyphicon glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                          {{ $rel->echoNomeCorretto($rel->getName()) }}
                        </a>

                        <a class="btn btn-sm btn-primary" href="{{ route('eval.new', ['evaluable_type' => 'Relation', 'evaluable_id' => $rel->id]) }} ">Valutazione rapida</a>

                        @if(\Auth::user()->favourites->contains($rel->id))
                        
                        <a href="{{ route('removeFavourite', ['rel_id' => $rel->id]) }}" class="btn btn-sm btn-success">
                            <span class="glyphicon glyphicon glyphicon glyphicon-star" aria-hidden="true"></span>
                        </a> 
                        @else
                        <a href="{{ route('addFavourite', ['rel_id' => $rel->id]) }}" class="btn btn-sm btn-primary">
                            <span class="glyphicon glyphicon glyphicon glyphicon-star-empty" aria-hidden="true"></span>
                        </a> 
                        
                        @endif
                        
                        <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
                    </div>

                 @endforeach

             </div>
         </div>



@endsection

@section('after-scripts')
    <script async src="{{asset('js/ajax_position_and_map_flyto.js')}}"></script>
@endsection