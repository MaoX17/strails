                    <h4>Ultime 10 Mie Valutazioni inserite:</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sentiero</th>
                                    <th>Data</th>
                                   <!-- <th>Dir.</th> -->
                                    <th>Descrizione</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                    
                                @foreach ($mine_evaluations as $eval)
                                    @foreach ($eval->relations as $evaluable)
                                     
                                
                                <tr class="{{$eval->rating}}" itemscope itemtype="http://schema.org/Review">
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
                                    
                                </tr>
                                    @endforeach

                                    @foreach ($eval->stravasegments as $evaluable)
                                    
                                    <tr class="{{$eval->rating}}" itemscope itemtype="http://schema.org/Review">
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
                                        
                                    </tr>
                                    @endforeach


                                @endforeach
                            </tbody>
                        </table>