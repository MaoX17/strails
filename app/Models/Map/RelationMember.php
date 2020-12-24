<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

class RelationMember extends Model
{
    //

    public $timestamps = false;
    protected $table = 'relationmembers';


//    public function relation()
  //  {
    //    return $this->hasMany('App\Models\Map\RelationTag', 'id', 'id');
    //}

//--



    /*
    public function relationable()
    {
        return $this->morphedByMany();
    }
*/

}
