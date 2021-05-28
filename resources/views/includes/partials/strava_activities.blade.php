                    <h4>Le tue ultime attività da strava: </h4>

                    <!-- <br> -->
                        <table class="table">
                            <thead>
                                <tr>

                                    <th>Data</th>
                                    <th>Titolo</th>
                                    <th>Attivit&agrave;</th>
                                    <th>Apri...</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($activities as $activity)



                                <tr>
                                    <td>
                                        {{ (new \DateTime($activity->start_date))->format('d/m/Y') }}
                                    </td>

                                    <td>{{$activity->name}}</td>
                                    <td>{{$activity->type}}</td>
                                    <td>
                                        @isset($activity->map->summary_polyline)
                                            <a class="btn btn-sm btn-info" href="{{ route('strava.stravaNearTrails', ['activity_id' => $activity->id ]) }}">
                                                <i class="fas fa-star"></i> Vai...
                                            </a>
                                        @endisset
                                    </td>

                                </tr>

                                @endforeach
                            </tbody>
                        </table>

                        <a href="{{ route('strava.index') }}" class="btn btn-sm btn-info">
                            Apri tutte le attività trovate su Strava
                        </a>
