<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

class Node extends Model
{
    //
    public $timestamps = false;
    protected $table = 'nodes';
    protected $primaryKey = 'id';


    public function tags()
    {
        return $this->hasMany('App\Models\Map\NodeTag', 'id', 'id');
    }

    public function ways()
    {
        return $this->belongsToMany('App\Models\Map\Way', 'waynodes', 'nodeid', 'id')
            ->withPivot('s');

    }


    public function coordinates_json()
    {
        $result = [
            'latitude' => $this->lat,
            'longitude' => $this->lon,
            'success' => 'success'
        ];

        return json_encode($result);
    }

    /*
    public function coordinates_array()
    {
        $result = [
            'latitude' => $this->lat,
            'longitude' => $this->lon
        ];

        return ($result);
    }
    */

    public function getName()
    {
        $name = '';
        try{
            $records =  ($this->tags()->where('k', 'name')->get()->first());
            $name = $records->v;
        }
        catch (\Exception $e){
            $name = '';
        }
        $ref = '';
        try{
            $records =  ($this->tags()->where('k', 'ref')->get()->first());
            $ref = $records->v;
        }
        catch (\Exception $e){
            $ref = '';
        }

        return $name." ".$ref;

    }





    public function getNearNodesPrec($lat, $lon, $type)
    {
        //$queryResult = \DB::select('CALL `dvpstrails`.`nodes_vicini`(?, ?, 100, 125)', [$lat,$lon]);
        $queryResult = \DB::select('CALL `dvpstrails`.`nodes_vicini`(?, ?, 10, 10,?)', [$lat,$lon,$type]);
        dd($queryResult);
        $result = collect($queryResult);
        //dd($result);
        return $result;
    }

    public function getNearNodes($lat, $lon, $type)
    {


        $around = '5000'; //metri
        
        //$latitude = session('lat');
        //$longitude = session('lng');

        if ($lat == "")
        {
            $lat = '43.888';
        }
        if ($lon == "")
        {
            $lon = '11.099';
        }
        $latitude = $lat;
        $longitude = $lon;



        $str = "around:".$around.",".$latitude.",".$longitude;
        
        $primo_passaggio = rawurlencode(utf8_encode($str));

        $secondo_passaggio = str_replace('.','%2E',$primo_passaggio);

        $parametri_url = $secondo_passaggio;

        

        // overpass query devo costruirla dinamicamente
    //$overpass = 'http://overpass-api.de/api/interpreter?data=%5Bout%3Ajson%5D%5Btimeout%3A60%5D%3B%28relation%5B%22route%22%7E%22mtb%7Chicking%7Cfoot%22%5D%28around%3A10000%2C43%2E88862049%2C11%2E0980714%29%3B%29%3Bout%3B%3E%3Bout%20skel%20qt%3B%0A';
        //$overpass = 'http://overpass-api.de/api/interpreter?data=%5Bout%3Ajson%5D%3B%28relation%5B%22route%22%7E%22mtb%7Chicking%7Cfoot%22%5D%28around%3A10000%2C43%2E88862049%2C11%2E0980714%29%3B%29%3Bout%3B%3E%3B%0A';
        $overpass = 'http://overpass-api.de/api/interpreter?data=%5Bout%3Ajson%5D%3B%28relation%5B%22route%22%7E%22mtb%7Chicking%7Cfoot%22%5D%28'.$parametri_url.'%29%3B%29%3Bout%3B%3E%3B%0A';
    // collecting results in JSON format
    $html = file_get_contents($overpass);
    $result = json_decode($html, true); // "true" to get PHP array instead of an object

    // elements key contains the array of all required elements
    $data = $result['elements'];

    $array_rel = array();
    $i = 0;
    foreach($data as $key => $row) {

        if($row['type'] == 'relation')
        {
            // id relation
            $relation_id = $row['id'];

            // name
            $relation_name = $row['tags']['name'];
            $relation_type = $row['tags']['route'];

            $array_rel[$i] = ['relid' => $relation_id, 'name' => $relation_name, 'type' => $relation_type];

            //echo "REL: ".$relation_name." id: ".$relation_id;

            $i++;

        }
        
    }

    //print_r($array_rel);
        $result = collect($array_rel);
        //dd($result);
        return $result;
    }




}
