@extends('frontend.layouts.app_map_leaflet') 
@section('after-styles')

@endsection
 
@section('content')

<div class="row">

    <div class="col-md-12">
        <div id="map"></div>
    </div>
    
</div>


<div class="row">
    <div class="col-md-12">
        <p class="text-center">Aumenta lo zoom per far comparire l'elenco dei sentieri e segmenti</p>
        <div class="loader" id="loader">
            <img src="{{asset('img/ajax-loader.gif')}}" height="42">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">


        <input class="lat" type="hidden" id="lat" name="lat" value="43.8886392">
        <input class="lon" type="hidden" id="lon" name="lon" value="11.0980594">

    </div>
    
</div>

<div class="row">
            
            <div id='strava_segment' class="col-md-6">
            </div>
        
            <div id="osm_relation" class="col-md-6">
            </div>
        
</div>



<!-- Button trigger modal 
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" data-eval_id="196951" data-eval_type="Relation">
  Valutazione Veloce
</button>
-->
<!-- Modal valuta -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Inserisci la tua valutazione:</h4>
            </div>
            <div class="modal-body">
                        
                        @if ($logged_in_user)
                
                        <form id="frm" method="post" action="{{ action('EvaluationController@storeModal') }}">
                            <input name="_token" type="hidden" value="{{ csrf_token() }}">
                            <input id="evaluable_id" name="evaluable_id" type="hidden" value="">
                            <input id="evaluable_type" name="evaluable_type" type="hidden" value="">
                
                            <div class="form-group">
                                <label for="direction" class="control-label col-md-2">Direzione</label>
                               
                                    <select class="form-control" name="direction" id="direction">
                                                <option value="down">Discesa</option>
                                                <option value="up">Salita</option>
                                            </select>
                               
                            </div>
                
                            <div class="form-group">
                                <label for="sport" class="control-label col-md-2">Attivit&agrave;</label>
                               
                                    <select class="form-control" name="sport" id="sport">
                                        <option value="mtb">MountainBike</option>
                                        <option value="trekking">Trekking</option>
                                    </select>
                               
                            </div>
                
                            <div class="form-group">
                                <label for="rating" class="control-label col-md-2">Valutazione</label>
                               
                                    <select class="form-control" name="rating" id="rating">           
                                        <option value="warning">Attenzione</option>
                                        <option value="success">Tutto OK</option>
                                        <option value="danger">Pericolo o Problema</option>
                                    </select>
                               
                            </div>
                
                
                            <div class="form-group">
                                <label for="desc" class="control-label col-md-2">Descrizione</label>
                               
                                    <input class="form-control" type="text" name="desc" id="desc" placeholder="Albero caduto a metà sentiero" required>
                               
                            </div>
                
                
                        </form>
                        @else
                        <p>Occorre eseguire il login per lasciare una valutazione</p>
                        <a class="btn btn-primary" href="{{route('frontend.auth.login')}}">Login</a>
                        @endif

            </div>
            @if ($logged_in_user)
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="glyphicon glyphicon-remove"></i> Close
                </button>
                
                <button id="invia" type="submit" class="btn btn-primary" data-dismiss="modal">
                    <i class="glyphicon glyphicon-ok"></i> Save
                </button>
            </div>
            @endif
        </div>
    </div>
</div>



<!-- Modal view -->
<div class="modal fade" id="myModalView" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Valutazioni:</h4>
            </div>
            <div class="modal-body">

                @if ($logged_in_user)

                <div id="eval"></div>
                
                @else
                <p>Occorre eseguire il login per visualizzare le valutazioni</p>
                <a class="btn btn-primary" href="{{route('frontend.auth.login')}}">Login</a> @endif

            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="glyphicon glyphicon-remove"></i> Close
                </button>
            </div>
            
        </div>
    </div>
</div>




<!-- modal upload img ---------------------------------------------------- -->
<div class="modal fade" id="myModalImg" tabindex="-1" role="dialog" aria-labelledby="myModalImgLabel">


    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalImgLabel">Inserisci immagine:</h4>
            </div>
            <div class="modal-body">

                @if ($logged_in_user)

                <form id="frmImg" method="post" action="{{ action('EvaluationController@storeModalImg') }}" enctype="multipart/form-data">
                    <input id="_token" name="_token" type="hidden" value="{{ csrf_token() }}">
                    <input id="evaluable_id" name="evaluable_id" type="hidden" value="">
                    <input id="evaluable_type" name="evaluable_type" type="hidden" value="">

                    <div class="form-group">
                        <label for="imgInputFile">Img input</label>
                        <input class="form-control" type="file" id="imgInputFile" name="imgInputFile">
                    </div>
                    
                </form>
                @else
                <p>Occorre eseguire il login per lasciare una valutazione</p>
                <a class="btn btn-primary" href="{{route('frontend.auth.login')}}">Login</a> @endif

            </div>
            @if ($logged_in_user)
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="glyphicon glyphicon-remove"></i> Close
                </button>

                <button id="inviaImg" type="submit" class="btn btn-primary" data-dismiss="modal">
                    <i class="glyphicon glyphicon-ok"></i> Save
                </button>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
 
@section('after-scripts')

@if (isset($logged_in_user->token_strava_access))
    <script async src="{{asset('js/ajax_mappa.js')}}"></script>
@else
    <script async src="{{asset('js/ajax_mappa_nostrava.js')}}"></script>
@endif

<script>
    //https://dvp.strails.it/eval/new/196951/Relation
    $('#myModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var eval_id = button.data('eval_id') // Extract info from data-* attributes
        var eval_type = button.data('eval_type') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('#evaluable_id').val(eval_id)
        modal.find('#evaluable_type').val(eval_type)

    })

    $('#invia').click(function(e) {
        $('#frm').submit();    
    });

    $('#frm').submit(function(e) {

   
    var form = $(this);
    var url = form.attr('action');

    $.ajax({
           type: "POST",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(data)
           {
               //alert(data.success); // show response from the php script.
               swal("Valutazione Inserita.");
           }
         });

    e.preventDefault(); // avoid to execute the actual submit of the form.

    $('#myModal').modal('hide');
 
    });

//-------------------------------------------------------------------------------------------
//---------view
     //https://dvp.strails.it/eval/new/196951/Relation
    $('#myModalView').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var eval_id = button.data('eval_id') // Extract info from data-* attributes
        var eval_type = button.data('eval_type') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('#evaluable_id').val(eval_id)
        modal.find('#evaluable_type').val(eval_type)

        var url = '/ajax/get_evaluations/' + eval_type + '/' + eval_id

$.get(url, function (data) {
    $('#eval').html(data);
    //console.log("DATA: "+JSON.stringify(data));
// For debugging purposes you can add : console.log(data); to see the output of your request
});
    
/*    $.ajax({
           type: "GET",
           url: url,
           data: form.serialize(), // serializes the form's elements.
           success: function(result)
           {
               //alert(data.success); // show response from the php script.
               swal("Valutazione Inserita.");
           }
         });
*/
    //e.preventDefault(); // avoid to execute the actual submit of the form.

    //$('#myModal').modal('hide');
 

    })


   
    
   


    //IMG ------------------------------------------------------------------------
    $('#myModalImg').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var eval_id = button.data('eval_id') // Extract info from data-* attributes
        var eval_type = button.data('eval_type') // Extract info from data-* attributes
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('#evaluable_id').val(eval_id)
        modal.find('#evaluable_type').val(eval_type)

    })

    $('#inviaImg').click(function(e) {
        $('#frmImg').submit();    
    });

    $('#frmImg').submit(function(e) {

   
    var form = $(this);
    var url = form.attr('action');

    //var formData = new FormData($(this)[0]);
    //var formData = "";
    //imgInputFile
    

    $.ajax({
        url: url,
        type: "POST",
        data:  new FormData(this),
        contentType: false,
        cache: false,
        processData:false,
        //beforeSend : function()
        //{
            //$("#preview").fadeOut();
            //$("#err").fadeOut();
        //},
        success: function(data)
        {
            console.log(data);
        },
        error: function(e) 
        {
            console.log(e);
            alert(e);
        }          
    });
    
    e.preventDefault(); // avoid to execute the actual submit of the form.

    $('#myModalImg').modal('hide');
 
    });

</script>


@endsection