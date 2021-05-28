<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

class Way extends Model
{
    //
    public $timestamps = false;
    protected $table = 'ways';
    protected $primaryKey = 'id';


    public function evaluations()
    {
        //return $this->morphToMany('App\Models\Trails\Evaluation', 'evaluable', 'evaluables','evaluable_id','evaluation_id')
        return $this->morphToMany('App\Models\Map\Evaluation', 'evaluable', 'evaluables','evaluable_id','evaluation_id')
            ->orderBy('updated_at', 'DESC');
    }



    public function relations()
    {
        //return $this->hasMany('App\Models\Map\RelationMember', 'id');
        //return $this->belongsToMany('App\Models\Map\RelationMember', 'ways', 'id', 'memberid');
        return $this->hasManyThrough(
            'App\Models\Map\Relation',
            'App\Models\Map\RelationMember',
            'memberid', // Foreign key on users table...
            'id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
        );

    }


    public function tags()
    {
        return $this->hasMany('App\Models\Map\WayTag', 'id', 'id');
    }


    public function members()
    {
        return $this->hasMany('App\Models\Map\WayNode', 'id', 'id');

    }

    public function nodes()
    {

        //Modifica necessaria - tutti si chiamano id e fa casino con la quesry
        //$this->hasManyThrough(BaseModelColumns::class,BaseModel::class,'id','report_model_id')->select('base_model_columns.id as column_id','base_model_columns.name as column_name','has_selection');

        //NOTE: ho dovutop chiamarlo nodeId altrimenti mi restituisce l'id della way e non del nodo

        return $this->belongsToMany('App\Models\Map\Node', 'waynodes', 'id', 'nodeid')
            ->withPivot('s');

        /*return $this->hasManyThrough(
            'App\Models\Map\Node',
            'App\Models\Map\WayNode',
            'id', // Foreign key on users table...
            'id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'nodeid' // Local key on users table...
        )->select('nodes.*','nodes.id as nodeId');
*/
        /*
        return $this->hasManyThrough(
            'App\Models\Map\Node',
            'App\Models\Map\WayNode',
            'id', // Foreign key on users table...
            'id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'nodeid' // Local key on users table...
        );*/
            //->select('nodes.id as nodeId');
            //->select('nodes.*','waynodes.id as wayid');

        //->select('base_model_columns.id as column_id','base_model_columns.name as column_name','has_selection');

    }


    public function getName()
    {
        $name = '';
        try{
            $records =  ($this->tags()->where('k', 'name')->get()->first());
            $name = $records->v;
        }
        catch (\Exception $e){
            $name = 'No Name';
        }
        $ref = '';
        try{
            $records =  ($this->tags()->where('k', 'ref')->get()->first());
            $ref = $records->v;
        }
        catch (\Exception $e){
            $ref = 'No Name';
        }

        return $name." ".$ref;

    }


    public  function toGeoJson()
    {

        $array_coordinate = array();
        $i = 0;
        foreach ($this->nodes()->orderBy('waynodes.s')->get() as $node)
        {
            //dd($node);
            $array_coordinate[$i] = [$node->lon, $node->lat];
            $i++;
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

        //dd($feature);

        $geojson = array(
            'type'      => 'FeatureCollection',
            'features'  => $feature
        );

        //dd($geojson);

        if (version_compare(phpversion(), '7.1', '>=')) {
            ini_set( 'serialize_precision',  9);
        }

        header('Content-type: application/json');
        $result = json_encode($geojson, JSON_NUMERIC_CHECK);

        //echo $result;
        return $result;

    }



}
