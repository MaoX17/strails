@extends('frontend.layouts.app_map_leaflet')

@section('after-styles')
    <style>

        #map { height: 300px; }

    </style>
@endsection

@section('content')



         <div class="row">
             <div class="col-md-8">
                <div id="map"></div>
             </div>
             <div class="col-md-4">

                 @foreach($near_rels_array as $rel)
                    <div>
                        <a href="{{route('viewRelationMap', ['relid' => $rel->id]) }}" class="btn btn-sm btn-{{$rel->lastStateClass()}}" aria-label="Left Align">
                            <i class="fas fa-map-marker-alt"></i>
                          {{ $rel->echoNomeCorretto($rel->getName()) }}
                        </a>

                        <input class="rel_visibility" id="{{ $rel->id }}" type="checkbox" name="my-checkbox" data-size="mini">
                        <label for="{{ $rel->id }}"></label>
                        <!-- non posso usare il badge dellla versione 4 di bootstrap perchè qui uso la 3 -->
                        <!-- <span class="badge">nr star</span> -->

                        <input class="rel" type="hidden" name="{{ $rel->id }}" value="{{ $rel->id }}">
                    </div>

                 @endforeach

             </div>
         </div>



@endsection

@section('after-scripts')
    <script async src="{{secure_asset('js/ajax_position_and_map_flyto.js')}}"></script>
@endsection
