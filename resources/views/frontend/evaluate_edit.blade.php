@extends('frontend.layouts.app_map_leaflet')

@section('content')


        <div class="row">
             <div class="col-md-12">
                 <h3>Modifica la tua valutazione</h3>

                 @if ($logged_in_user)

                 <p>Valutazione:</p>
                 <form method="post" action="{{ action('EvaluationController@update') }}">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                    <input name="evaluation_id" type="hidden" value="{{ $evaluation->id }}" />


                    <div class="form-group">
                        <label for="direction" class="control-label col-md-2">Direzione</label>
                        <div class="col-md-10">
                            <select class="form-control" name="direction" id="direction">
                                <option {{ ($evaluation->direction == 'down' ? 'selected' : '') }} value="down">Discesa</option>
                                <option {{ ($evaluation->direction == 'up' ? 'selected' : '') }} value="up">Salita</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sport" class="control-label col-md-2">Attivit&agrave;</label>
                        <div class="col-md-10">
                            <select class="form-control" name="sport" id="sport">
                                <option {{ ($evaluation->sport == 'mtb' ? 'selected' : '') }} value="mtb">MountainBike</option>
                                <option {{ ($evaluation->sport == 'trekking' ? 'selected' : '') }} value="trekking">Trekking</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="rating" class="control-label col-md-2">Valutazione</label>
                        <div class="col-md-10">
                            <select class="form-control" name="rating" id="rating">

                                <option {{ ($evaluation->rating == 'warning' ? 'selected' : '') }} value="warning">Attenzione</option>
                                <option {{ ($evaluation->rating == 'success' ? 'selected' : '') }} value="success">Tutto OK</option>
                                <option {{ ($evaluation->rating == 'danger' ? 'selected' : '') }} value="danger">Pericolo o Problema</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="desc" class="control-label col-md-2">Valutazione Descrittiva</label>
                        <div class="col-md-10">
                            <input value="{{ $evaluation->rating_desc }}" class="form-control" type="text" name="desc" id="desc" placeholder="Albero caduto a metà sentiero" required>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="note" class="control-label col-md-2">note: </label>
                        <div class="col-md-10">
                            <input value="{{ $evaluation->note }}" class="form-control" type="text" name="note" id="note" placeholder="Commento...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="send" class="control-label col-md-2">Modifica: </label>
                        <div class="col-md-10">
                            <input class="btn btn-info form-control" type="submit" name="send" id="send" value="Modifica">
                        </div>
                    </div>

                 </form>




                @endif

             </div>
         </div> <!-- row -->


@endsection

@section('after-scripts')
   <!--  <script src="{{secure_asset('js/ajax_rel_visible_leaf.js')}}"></script> -->
@endsection
