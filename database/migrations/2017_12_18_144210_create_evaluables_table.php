<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluables', function (Blueprint $table) {
            //$table->increments('id');
            $table->increments('evaluation_id');

            $table->morphs('evaluable'); //Adds unsigned INTEGER commentable_id and STRING commentable_type.
            //posso commentare relations ways o nodes (in base al type)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evaluables');
    }
}
