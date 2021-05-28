<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class Relation extends Model
{
    //
    public $timestamps = false;
    protected $table = 'relations';
    protected $primaryKey = 'id';

    //protected $morphClass = 'way';

    public function evaluations()
    {
        //return $this->morphToMany('App\Models\Trails\Evaluation', 'evaluable');
        //return $this->morphToMany('App\Models\Trails\Evaluation', 'evaluable', 'evaluables','evaluable_id','evaluation_id')
        return $this->morphToMany('App\Models\Map\Evaluation', 'evaluable', 'evaluables','evaluable_id','evaluation_id')
            ->orderBy('updated_at', 'DESC');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\Map\RelationTag', 'id', 'id');
    }

    public function members()
    {
        return $this->belongsToMany('App\Models\Map\RelationMember', 'relations', 'id', 'id');

    }


    public function favoriteFollowingUsers()
    {
        return $this->belongsToMany('App\Models\Access\User\User', 'favourites', 'relation_id', 'user_id')->withTimeStamps();

    }

    public function getEvaluationType()
    {
        return class_basename(get_class($this));
    }



    //todo: OK! dovrebbe convertirmi i risultati in model Way in modo che siano oggetti valoreizzati
    /*public function ways()
    {
        return $this->members()->where('type', 'way');

    }

    public function relation()
    {
        return $this->members()->where('type', 'relation');
    }
    */


    public function ways()
    {
        //return $this->belongsToMany('App\Models\Map\Way', 'relationmembers', 'id', 'memberid');
        return $this->belongsToMany('App\Models\Map\Way', 'relationmembers', 'id', 'memberid')
            ->withPivot('s');

    }

    public function relations()
    {
        //return $this->belongsToMany('App\Models\Map\Relation', 'relationmembers', 'id', 'memberid');
        return $this->belongsToMany('App\Models\Map\Relation', 'relationmembers', 'id', 'memberid')
            ->withPivot('s');
    }


    /*
    public function scopeMember($query)
    {
        return $query
            ->when($this->type === 'way',function($q){
                return $q->with('wayMember');
            })
            ->when($this->type === 'school',function($q){
                return $q->with('schoolProfile');
            })
            ->when($this->type === 'academy',function($q){
                return $q->with('academyProfile');
            },function($q){
                return $q->with('institutionProfile');
            });
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



    public  function toGeoJson()
    {

        $array_coordinate = array();
        $i = 0;
        foreach ($this->ways()->orderBy('relationmembers.s')->get() as $way) {
            foreach ($way->nodes()->orderBy('waynodes.s')->get() as $node)
            {
                $array_coordinate[$i] = [$node->lon, $node->lat];
                $i++;
            }
            //$array_coordinate = array_unique($array_coordinate);
        }



        $feature = array(array(
            'id' => $this->id,
            'type' => 'Feature',
            'geometry' => array(
                'type' => 'LineString',
                'coordinates' => $array_coordinate
            ),
            # Pass other attribute columns here
            'properties' => array(
                'name' => $this->getName(),
                'description' => $this->getName()
            )
        ));

        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);

        return $result;

    }



    public  function toGeoJson3()
    {

        $array_coordinate = array();

        $j = 0;
        foreach ($this->ways()->orderBy('relationmembers.s')->get() as $way) {
            $i = 0;
            //dd($way);
            $array_coordinate = array();
            foreach ($way->nodes()->orderBy('waynodes.s')->get() as $node)
            {
                $array_coordinate[$i] = [$node->lon, $node->lat];
                $i++;
            }

            $feature[$j] = array(
                'id' => $way->id,
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $array_coordinate
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'rel_id' => $this->id,
                    'name' => $this->getName(),
                    'description' => $this->getName().' - '.$way->pivot->s
                )
            );

            $j++;


        }

        $geojson = array(
            'type'      => 'FeatureCollection',
//            'metadata'  => 'prova',
            'features'  => $feature
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);

        return $result;

    }





    public  function toGeoJsonFull()
    {
        //echo $this->id." - ";
        $feature2 = array();
        $array_coordinate = array();
        $j = 0;
        foreach ($this->ways()->orderBy('relationmembers.s')->get() as $way) {
            $i = 0;
            $array_coordinate = array();
            foreach ($way->nodes()->orderBy('waynodes.s')->get() as $node)
            {
                $array_coordinate[$i] = [$node->lon, $node->lat];
                $i++;
            }
            $feature2[$j] = array(
               // 'id' => $way->id,
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $array_coordinate
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $this->echoNomeCorretto($this->getName()),
                    'description' => $this->echoNomeCorretto($this->getName().' - '.$way->pivot->s)
                )
            );
            $j++;
        }
        //dd($feature2);
        return $feature2;
    }


    public  function toGeoJsonFull2()
    {
        //echo $this->id." - ";
        $feature2 = array();
        
        $j = 0;
        $i = 0;
        $array_coordinate = array();
        $array_way_id_test = array();
        $array_node_id_test = array();


        foreach ($this->ways()->orderBy('relationmembers.s')->get() as $way) {

            //dd($way->nodes()->orderBy('waynodes.s')->toSql());
            
            foreach ($way->nodes()->orderBy('waynodes.s')->get() as $node)
            {
                
                $array_coordinate[$i] = [$node->lon, $node->lat];
                $array_node_id_test[$i] = $node->id;
                $i++;
            }
            
            $array_way_id_test[$j] = $way->id;

            $j++;
        }
        $feature2 = array(
                'id' => $way->id,
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $array_coordinate
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $this->echoNomeCorretto($this->getName()),
                    'description' => $this->echoNomeCorretto($this->getName()),
                    'test' => $array_node_id_test,
                    'center' => (isset($array_coordinate[0]) ? $array_coordinate[round(count($array_coordinate)/2)] : [44.0,12.0])
                   //'center' => (isset($array_coordinate[0]) ? $array_coordinate[0] : [44.0,12.0])
                )   
            );
            //echo(count($feature2['geometry']['coordinates']))."\n\r";
            if (count($feature2['geometry']['coordinates']) == 0){
                return null;
            } 
            else {
                return $feature2;
            }
    }




    public  function ptToGeoJsonFull()
    {       
        $feature = array();
        $array_coordinate = $this->getCoordinate();
            $feature = array(
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => $array_coordinate
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $this->getName(),
                    'rel_id' => $this->id
                )
            );

        return $feature;

    }



/**
 * 
 */
    public  function ptToGeoJson()
    {

        
        $array_coordinate = $this->getCoordinate();

        
            $feature = array(
                
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'Point',
                    'coordinates' => $array_coordinate
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $this->getName()
                )
            );


        $geojson = $feature;

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);

        return $result;

    }







    public  function getCoordinateJson()
    {
        $first_way = $this->ways()->first();
        $first_node = $first_way->nodes()->first();
        //$array_coordinate = array('center' => [$first_node->lon, $first_node->lat]);
        $array_coordinate = [$first_node->lon, $first_node->lat];
        $result = json_encode($array_coordinate, JSON_NUMERIC_CHECK);
        //dd($result);
        return $result;

    }

    public  function getCoordinate()
    {
        $array_coordinate = [0.0, 0.0];
        $first_way = $this->ways()->first();
        if (isset($first_way)){
            $first_node = $first_way->nodes()->first();
            //$array_coordinate = array('center' => [$first_node->lon, $first_node->lat]);
            $array_coordinate = [$first_node->lon, $first_node->lat];
        }
        
        $result = $array_coordinate;
        //dd($result);
        return $result;

    }


    //TODO: getCenterCoordinate - conto le way e prendo quella centrale


    public  function getXml($relid)
    {
        $fileContents= file_get_contents('https://www.openstreetmap.org/api/0.6/relation/'.$relid.'/full');
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);
        

        return $simpleXml;



    }


/**
 * non funziona un cazzo... mischia i punti provare con relid 194476 e butta
 * il risultato su http://geojson.io/#map=2/20.0/0.0
 */
    public function fromXmltoGeoJson($relid)
    {

        $fileContents= file_get_contents('https://www.openstreetmap.org/api/0.6/relation/'.$relid.'/full');
        $fileContents = str_replace(array("\n", "\r", "\t"), '', $fileContents);
        $fileContents = trim(str_replace('"', "'", $fileContents));
        $simpleXml = simplexml_load_string($fileContents);

$array_coordinate = array();

        $i = 0;
        $array_coordinate = array();
        foreach ($simpleXml->node as $node) {
          
           // print_r((string) $node['lon']);

                   
            $array_coordinate[$i] = [(string) $node['lon'], (string) $node['lat']];
            $i++;
            }
            
            


            foreach ($simpleXml->relation->tag as $key => $value) {
                /*
                print_r($key);
                print_r($value);

                print_r((string) $value['k']);
                print_r((string) $value['v']);
*/
if ((string) $value['k'] == "name")
                    {
                        $relname = ((string) $value['v']);    
                    }
  
          }

            //dd($simpleXml);

            $feature = array(
                'id' => (string) $simpleXml->relation['id'],
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $array_coordinate
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $relname,
                    'description' => $relname
                )
            );

            


        

        $geojson = array(
            'type'      => 'Feature',
            'id' => (string) $simpleXml->relation['id'],
                'type' => 'Feature',
                'geometry' => array(
                    'type' => 'LineString',
                    'coordinates' => $array_coordinate
                ),
                # Pass other attribute columns here
                'properties' => array(
                    'name' => $relname,
                    'description' => $relname
                )
        );

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);

        return $result;


    }


    public  function saveGeoJson($geojson)
    {
        
        //\App\Models\Map::TestJson

    }


    //sostituire questo metodo con lastStateClass in tutte le chiamate
    public function lastState()
    {
        $lastEval = $this->evaluations()->first();
        if (\is_object($lastEval))
        {
            $return = $lastEval->rating;
        }
        else {
            $return = "info";
        }

        //dd($return);
        return $return;
        
    }


    public function lastStateClass()
    {
        $lastEval = $this->evaluations()->first();
        if (\is_object($lastEval))
        {
            $return = $lastEval->rating;
        }
        else {
            $return = "info";
        }

        //dd($return);
        return $return;
        
    }


    public function echoNomeCorretto($str)
    {
        return str_replace ( 'Ã' , 'à' , $str);
    }

}
