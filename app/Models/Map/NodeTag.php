<?php

namespace App\Models\Map;

use Illuminate\Database\Eloquent\Model;

class NodeTag extends Model
{
    //
    public $timestamps = false;
    protected $table = 'nodetags';


    public function node()
    {
        return $this->belongsTo('App\Models\Map\Node');
    }

}
