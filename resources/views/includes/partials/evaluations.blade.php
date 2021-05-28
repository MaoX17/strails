                    <h4>Ultime Valutazioni inserite:</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Sentiero</th>
                                    <th scope="col">Data</th>
                                   <!-- <th scope="col">Dir.</th> -->
                                    <th scope="col">Descrizione</th>
                                    <th scope="col">Utente</th>

                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($evaluations as $eval)
                                    @foreach ($eval->relations as $evaluable)


                                <tr class="table-{{$eval->rating}}" itemscope itemtype="http://schema.org/Review">
                                    <td>
                                        <span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
                                            <a href="{{route('viewRelationMap',['relid' => $evaluable->id]) }}" itemprop="url">
                                                <span itemprop="name">
                                                    {{ \App\Models\Map\Relation::find($evaluable->id)->getName() }}
                                                </span>
                                            </a>
                                        </span>
                                    </td>
                                    <td> {{ $eval->updated_at->format('d/m/Y') }}</td>
                                  <!--   <td>{{ $eval->direction }}</td> -->
                                    <td>
                                        <span itemprop="reviewBody">
                                            {{ $eval->rating_desc}}
                                        </span>
                                    </td>
                                    <td>
                                        <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                                            <span itemprop="name">
                                                {{ $eval->author->name}}
                                            </span>
                                        </span>
                                        @if($eval->author->isTop())
                                            <i class="fa fa-trophy" aria-hidden="true"></i>
                                        @endif
                                        <span class="badge badge-secondary"> {{$eval->author->evaluations()->count()}} </span>
                                    </td>


                                </tr>
                                    @endforeach

                                    @foreach ($eval->stravasegments as $evaluable)

                                    <tr class="table-{{$eval->rating}}" itemscope itemtype="http://schema.org/Review">
                                        <td>
                                            <span itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
                                                @if (isset($logged_in_user->token_strava_access))
                                                    <a href="{{route('viewSegmentMap',['segment_id' => $evaluable->id]) }}" itemprop="url">
                                                        <span itemprop="name">
                                                             {{ \App\Models\Map\StravaSegment::find($evaluable->id)->getName() }}
                                                        </span>
                                                    </a>

                                                @else
                                                <span itemprop="name">
                                                    {{ \App\Models\Map\StravaSegment::find($evaluable->id)->getName() }}
                                                </span>
                                                @endif
                                            </span>
                                        </td>
                                    <td> {{ $eval->updated_at->format('d/m/Y') }}</td>
                                    <!-- <td>{{ $eval->direction }}</td> -->
                                        <td>
                                            <span itemprop="reviewBody">
                                                {{ $eval->rating_desc}}
                                            </span>
                                        </td>
                                        <td>
                                            <span itemprop="author" itemscope itemtype="http://schema.org/Person">
                                                <span itemprop="name">
                                                    {{ $eval->author->name}}
                                                </span>
                                            </span>
                                            @if($eval->author->isTop())
                                            <i class="fa fa-trophy" aria-hidden="true"></i> @endif
                                            <span class="badge badge-secondary"> {{$eval->author->evaluations()->count()}} </span>
                                        </td>


                                    </tr>
                                    @endforeach


                                @endforeach
                            </tbody>
                        </table>
