<?php

namespace App\Models\Trails;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    //

    protected $table = 'evaluations';
    protected $primaryKey = 'id';
    


    public function ways()
    {
        return $this->morphedByMany('App\Models\Map\Way', 'evaluable');
    }

    public function relations()
    {
        return $this->morphedByMany('App\Models\Map\Relation', 'evaluable');
    }

    public function author()
    {
        return $this->belongsTo('App\Models\Access\User\User', 'user_id', 'id');
    }


}
