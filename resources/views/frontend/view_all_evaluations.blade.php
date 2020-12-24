@extends('frontend.layouts.app')

@section('content')


    
    <div class="row">
        <div classs="col-md-12">
            <h4 itemprop="about">Tutte le Valutazioni inserite:</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sentiero</th>
                            <th>Data</th>
                            <th>Direzione</th>
                            <th>Attivit&agrave;</th>
                            <th>Descrizione</th>
                            <th>Utente</th>
                        </tr>
                    </thead>
                    <tbody>                                
                    @foreach ($evaluations as $eval) 
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
                                <td>{{ $eval->direction }}</td>
                                <td>
                                    {{ $eval->sport }}
                                </td>
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
                                    <span class="badge"> {{$eval->author->evaluations()->count()}} </span>
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
                                <td> {{ $eval->updated_at->format('d/m/Y') }} </td>
                                <td>{{ $eval->direction }}</td>
                                <td>{{ $eval->sport }}</td>
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
                                    <span class="badge"> {{$eval->author->evaluations()->count()}} </span>
                                </td>
                            </tr>
                        @endforeach 
                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('includes.partials.stat')
    @include('includes.partials.follow_us_button')
    @include('includes.partials.credits')
    @include('includes.partials.app_description')
    

@endsection

