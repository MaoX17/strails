@extends('frontend.layouts.app_map_leaflet')

@section('content')

         <h1>Strails - Social Trails</h1>
         <p class="lead">Where can u go for fun</p>

         <hr>

         <div class="row">
             <div class="col-md-12">
                <div id="map"></div>
             </div>
         </div>


        <div class="row"></div>

        <div class="row">
             <div class="col-md-1"></div>
             <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 align-center">
                 <form method="post" action="{{ route('search') }}">
                     <input type="hidden" name="_token" value="{{ csrf_token() }}">
                     <div class="input-group">
                         <input type="text" name="search" class="form-control typeahead" id="search" placeholder="Search trails...">
                         <div class="input-group-btn">
                             <button type="submit" class="btn btn-default">
                                 <span class="glyphicon glyphicon glyphicon-search"></span>
                             </button>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="col-md-1"></div>
        </div>


@endsection

@section('after-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

    <script type="text/javascript">
        var path = "{{ route('autocomplete') }}";

//        $('input.typeahead').typeahead({
        $('#search.typeahead').typeahead({
            source:  function (query, process) {
                return $.get(path, { query: query }, function (data) {
                    console.log(data);
                    console.log(data.id);
                    return process(data);

                });
            }
        });





    </script>
    
@endsection