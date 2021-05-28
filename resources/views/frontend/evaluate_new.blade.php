@extends('frontend.layouts.app_map_leaflet')

@section('content')



             <!--
        <div class="row">
             <div class="col-md-12">
                <div id="map"></div>
             </div>
        </div>
            -->
        <div class="row">
             <div class="col-md-12">
                <h3>{{ $evaluable->echoNomeCorretto($evaluable->getName()) }}
                <small>
                Stato Attuale:
                @if ($evaluable->getEvaluationType() == 'Relation' )
                    <a href="{{route('viewRelationMap', ['relid' => $evaluable->id]) }}" class="btn btn-sm btn-{{$evaluable->lastStateClass()}}" aria-label="Left Align">
                        <i class="fas fa-map-marker-alt"></i>
                    </a>
                @elseif($evaluable->getEvaluationType() == 'StravaSegment')
                    <a href="{{route('viewSegmentMap', ['segment_id' => $evaluable->id]) }}" class="btn btn-sm btn-{{$evaluable->lastStateClass()}}"
                        aria-label="Left Align">
                    <i class="fas fa-map-marker-alt"></i>
                    </a>
                @endif
                </small>
                 </h3>
                 @if ($logged_in_user)

                 <p>Inserisci la tua valutazione:</p>
                 <form method="post" action="{{ action('EvaluationController@store') }}">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}" />
                    <input name="evaluable_id" type="hidden" value="{{ $evaluable->id }}" />
                    <input name="evaluable_type" type="hidden" value="{{ class_basename(get_class($evaluable)) }}" />


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
                                <option value="danger">Pericolo o Problema</option>
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

        </div>
        </div>
        <div class="row">

            Ultime Valutazioni:<br>
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

                @foreach ($evaluable->evaluations as $eval)
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

            @endif

         </div> <!-- row -->





@endsection

@section('after-scripts')
    <!-- <script src="{{secure_asset('js/ajax_rel_visible_leaf.js')}}"></script> -->
@endsection
