<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;
 
class Evaluation extends Model
{
    //
    protected $table = 'evaluations';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'direction', 'sport', 'rating', 'rating_desc', 'lat', 'lon', 'note'];
    

    public function relations()
    {
        return $this->morphedByMany('App\Models\Map\Relation', 'evaluable');
    }

    
    public function ways()
    {
        return $this->morphedByMany('App\Models\Map\Way', 'evaluable');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Access\User\User', 'user_id', 'id');
    }

    public function stravasegments()
    {
        return $this->morphedByMany('App\Models\Map\StravaSegment', 'evaluable');
    }

    
}
