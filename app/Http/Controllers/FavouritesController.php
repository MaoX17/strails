<?php


namespace App\Http\Controllers;

use App\Models\Map\Favourite;
use Illuminate\Http\Request;

use App\Models\Map\Relation;
use App\Models\Map\StravaSegment;

class FavouritesController extends Controller
{


    public function __construct()
    {
        //$this->middleware('auth');
    }


    //TODO: il rel_id potrebbe essere lo stesso fra un segmento strava e una relation.... 
    //occorre correggere al più presto

    public function favouriteRel($rel_id, $rel_type)
    {
        //Relation OSM:
        if ($rel_type == 'Relation'){ 
            if (! \Auth::user()->favourites->contains($rel_id)) {

            \Auth::user()->favourites()->attach($rel_id);
                $relation = Relation::find($rel_id);
                $first_node = $relation->getCoordinate();
            }
        }
        //strava segment:
        elseif ($rel_type == 'StravaSegment') {
            if(! \Auth::user()->stravafavourites->contains($rel_id)){
                \Auth::user()->stravafavourites()->attach($rel_id);
                $relation = StravaSegment::firstOrCreate(['id' => $rel_id]);
                $relation->updateTmpSegment();
                //dd($relation);
                //$relation = StravaSegment::find($rel_id);
                $first_node = $relation->getCoordinate();
            }
        }

        
        return back();
        
    }

    public function unFavouriteRel($rel_id, $rel_type)
    {
        //Relation OSM:
        if ($rel_type == 'Relation'){
            if (\Auth::user()->favourites->contains($rel_id)) {
                \Auth::user()->favourites()->detach($rel_id);
                $relation = Relation::find($rel_id);
                $first_node = $relation->getCoordinate();
            }
        }
        //strava segment:
        elseif ($rel_type == 'StravaSegment') {    
            if (\Auth::user()->stravafavourites->contains($rel_id)){
                \Auth::user()->stravafavourites()->detach($rel_id);
                $relation = StravaSegment::find($rel_id);
                $first_node = $relation->getCoordinate();
            }
        }

        return back();

    }




}
