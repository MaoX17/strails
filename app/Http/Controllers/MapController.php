<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Map\Relation;
use App\Models\Map\StravaSegment;

use App\Models\Map\Way;

use Illuminate\Support\Facades\Session;
use App\Models\Access\User\SocialLogin;
use App\Models\Access\User\User;

use Illuminate\Support\Facades\Storage;

class MapController extends Controller
{
    //
    /*
    public function view()
    {
        $me = new \App\Models\Map\Position(session('lat'),session('lng'));
        return view('view_map')
            ->with('rel',$rel);

    }

*/



    public function start()
    {
        //Posizione utente:
        $me = new \App\Models\Map\Position(session('lat'),session('lng'));
        //nodi vicini:
        $nodes = $me->getNearNodes(session('lat'),session('lng'));

        $i = 0;
        $array_id_ways = array();

        foreach ($nodes as $node)
        {
            //echo $node->id."<br>";
            $near_node = \App\Models\Map\Node::find($node->id);
            $tmp_ways = $near_node->ways()->orderBy('waynodes.s')->get();

            foreach ($tmp_ways as $tmp_way){
                $array_id_ways[$i] = $tmp_way->id;
                $i++;
            }
        }

        //TODO: commentato per provare l'ordine dei sentieri
        $result = $array_id_ways;
        //$result = array_unique($array_id_ways);
        //$array_id_ways = $result;
        //dd($result);

        $ways = \App\Models\Map\Way::whereIn('id', $result)->get();
        //dd($ways);

        //todo: SONO MEMBRI DI UNA RELATION?
        $rel_array = collect();
        $i = 0;
        $array_id_rels = array();

        foreach ($ways as $way)
        {
            foreach ($way->relations()->get() as $rel)
            {
                $array_id_rels[$i] = $rel->id;
                $rel_array->push($rel);
                $i++;
            }
        }
        //TODO: commentato per provare l'ordine dei sentieri
        $result = $array_id_rels;
        //$result = array_unique($array_id_rels);
        //$array_id_rels = $result;
        //dd($result);
        $rels = \App\Models\Map\Relation::whereIn('id', $result)->get();
        //dd($rels);

        //TODO: togliere le way comprese nella relation?????




        return view('frontend.index3')
            ->with('near_ways_array',$ways)
            ->with('near_rels_array',$rels);

    }



    public function sourceWayGeoJson($wayid)
    {
        $way = Way::find($wayid);
        return $way->toGeoJson();

    }

    public function sourceRelGeoJson($relid)
    {
        $rel = Relation::find($relid);
        return $rel->toGeoJson();

    }

    public function xmlRelGeoJson($relid)
    {
        $rel = Relation::find($relid);
        return $rel->toGeoJson();

    }

    public function sourceRelGeoJson3($relid)
    {
        $rel = Relation::find($relid);
        return $rel->toGeoJson3();

    }

    public function sourceRelGeoJsonFull()
    {
        $rels = Relation::all();
        //dd($rels);
        //scrivo la testa del file
        $feature = array();
        $i=0;
        foreach ($rels as $rel) {
            $array_tmp[$i] = $rel->toGeoJsonFull();
            $feature = array_merge($feature, $array_tmp[$i]);
            $i++;
        }

        dd($feature);


        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);
        //scrivo la coda del file




    }

    public function sourceRelNodeGeoJson($relid)
    {
        $rel = Relation::find($relid);
        return $rel->getCoordinateJson();

    }

    public function sourceRelNodeGeoJson3($relid)
    {
        $rel = Relation::find($relid);
        //$coordinate = $rel->getCoordinateJson();
        return $rel->ptToGeoJson();

    }




    public function nearTrails(){
        //Posizione utente:
        //dd(session('type'));
        //dd("xx");
        $me = new \App\Models\Map\Position(session('lat'),session('lng'));
        $nodes = $me->getNearNodes(session('lat'),session('lng'),session('type'));

        $i = 0;
        $j = 0;
        $array_id_ways = array();
        $array_id_rels = array();


        foreach ($nodes as $node)
        {
            //se ho settato la tipologia di sentiero
            if (session('type') != ""){
                //filtro per tipo sentiero
                if ($node['type'] == session('type'))
                {
                    $array_id_rels[$j] = $node['relid'];
                    $j++;
                }
            }
            //altrimenti prendo tutti i sentieri
            else {
                    $array_id_rels[$j] = $node['relid'];
                    $j++;
            }
        }
        $result = $array_id_rels;
        $rels = \App\Models\Map\Relation::whereIn('id', $result)->get();

        //TODO: togliere le way comprese nella relation?????

        return view('frontend.near_trails')
            ->with('near_rels_array',$rels);

    }



    public function fastNearTrails(){
        //Posizione utente:
        //dd(session('type'));
        $me = new \App\Models\Map\Position(session('lat'),session('lng'));
        $nodes = $me->getNearNodes(session('lat'),session('lng'),session('type'));

        $i = 0;
        $j = 0;
        $array_id_ways = array();
        $array_id_rels = array();


        foreach ($nodes as $node)
        {
            //se ho settato la tipologia di sentiero
            if (session('type') != ""){
                //filtro per tipo sentiero
                if ($node['type'] == session('type'))
                {
                    $array_id_rels[$j] = $node['relid'];
                    $j++;

                }
            }
            //altrimenti prendo tutti i sentieri
            else {
                    $array_id_rels[$j] = $node['relid'];
                    $j++;

            }


        }

        $result = $array_id_rels;

        $rels = \App\Models\Map\Relation::whereIn('id', $result)->get();

        //TODO: togliere le way comprese nella relation?????

        return view('frontend.fast_near_trails')
            ->with('near_rels_array',$rels);

    }


    public function viewSegmentMapLeaf($segment_id)
    {
        //$segment = StravaSegment::find($segment_id);
        $segment = StravaSegment::firstOrCreate(['id' => $segment_id]);
        $segment->updateTmpSegment();
        $first_node = $segment->getCoordinate();

        return view('frontend.view_map_segment')
            ->with('segment',$segment)
            ->with('first_node',$first_node);

    }



    public function viewRelationMapLeaf($relid)
    {


        $relation = Relation::find($relid);
        $first_node = $relation->getCoordinate();

        return view('frontend.view_map_rel_leaf')
            ->with('rel',$relation)
            ->with('first_node',$first_node);

    }

    public function viewRelationMap($relid)
    {

        $relation = Relation::find($relid);
        $first_node = $relation->getCoordinate();

        //dd($first_node);

        return view('frontend.view_map_rel')
            ->with('rel',$relation)
            ->with('first_node',$first_node);

    }




    //mappa completa
    public function mappashow(){
        if(! (\Auth::user())) {
            $alert_nologin = "Attenzione. Per poter utilizzare al meglio l'applicazione è necessario il login. Altrimenti alcune funzioni non saranno attive.";
            session()->flash('flash_danger', $alert_nologin);
        }
        elseif ((User::find(\Auth::user()->id)->token_strava_access) == "") {
            $alert_nostrava = "Attenzione! Per poter visualizzare e commentare i segmenti Strava occorre cliccare su \"Connect with Strava\" e concedere i permessi.";
            session()->flash('flash_danger', $alert_nostrava);
        }

        return view('frontend.mappa');

    }






}
