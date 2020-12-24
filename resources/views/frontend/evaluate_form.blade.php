@extends('frontend.layouts.app_map_leaflet')

@section('content')

         <div class="row">
             <!--
             <div class="col-md-9">
                <div id="map"></div>
             </div>
            -->
             <div class="col-md-12">
                 <h3>{{ $way->getName() }}</h3>
                 Stato Attuale: <span class="label label-warning"><i class="fa fa-circle-thin" aria-hidden="true"></i></span> <br>
                 Ultimi Commenti<br>
                 <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Direzione</th>
                            <th>Attivit&agrave;</th>
                            <th>Descrizione</th>
                            <th>Utente</th>
                        </tr>
                    </thead>
                    <tbody>

                 @foreach ($way->evaluations as $eval)
                            <tr class="{{$eval->rating}}">
                                <td> {{ $eval->updated_at }}</td>
                                <td>{{ $eval->direction }}</td>
                                <td>{{ $eval->sport }}</td>
                                <td>{{ $eval->rating_desc}}</td>
                                <td>{{ $eval->author->fullname}}</td>
                            </tr>
                          
                 @endforeach
                 </tbody>
                </table>
                 ..

                 @if ($logged_in_user)

                 <p>Valutazione:</p>
                 <form method="post" action="{{ action('EvaluationController@storeEvaulationWay') }}">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                    <input name="wayid" type="hidden" value="{{ $way->id }}" />
                    

                    <div class="form-group">
                        <label for="direction" class="control-label col-md-2">Direzione</label>
                        <div class="col-md-10">
                            <select class="form-control" name="direction" id="direction">
                                <option value="down">Discesa</option>
                                <option value="up">Salita</option>
                            </select>    
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sport" class="control-label col-md-2">Attivit&agrave;</label>
                        <div class="col-md-10">
                            <select class="form-control" name="sport" id="sport">
                                <option value="mtb">MountainBike</option>
                                <option value="trekking">Trekking</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="rating" class="control-label col-md-2">Valutazione</label>
                        <div class="col-md-10">
                            <select class="form-control" name="rating" id="rating">
                                
                                <option value="warning">Attenzione</option>
                                <option value="success">Tutto OK</option>
                                <option value="alert">Pericolo o Problema</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="desc" class="control-label col-md-2">Valutazione Descrittiva</label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="desc" id="desc" placeholder="Albero caduto a metà sentiero" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="note" class="control-label col-md-2">note: </label>
                        <div class="col-md-10">
                            <input class="form-control" type="text" name="note" id="note" placeholder="Commento...">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="send" class="control-label col-md-2">Invia: </label>
                        <div class="col-md-10">
                            <input class="btn btn-info form-control" type="submit" name="send" id="send" value="invia">
                        </div>
                    </div>

                 </form>
                



                @endif
                 
             </div>
         </div> <!-- row -->



        <div class="row">
             <div class="col-md-1"></div>
             <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10 align-center">
                     <br>
                     {{ $way->getName() }}
                     {{ $way->id }}
                     <a href="http://www.openstreetmap.org/way/{{$way->id}}" target="_blank">{{ $way->id }}</a>

                     <span class="label label-warning"><i class="fa fa-circle-thin" aria-hidden="true"></i></span>

                 <input id="rel" type="hidden" value="{{ $way->id }}">
                 
             </div>
             <div class="col-md-1"></div>
        </div>


@endsection

@section('after-scripts')
    <script src="{{asset('js/ajax_rel_visible_leaf.js')}}"></script>
@endsection