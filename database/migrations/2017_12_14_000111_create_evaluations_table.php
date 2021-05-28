<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {

            $table->increments('id');


            $table->integer('user_id')->unsigned();


            //$table->boolean('favorite')->nullable();
            $table->string('direction')->nullable(); //salita - discesa
            $table->string('sport')->nullable(); //trekking - MTB

            $table->string('rating'); //verde giallo rosso
            $table->string('rating_desc'); //da scegliere fra un elenco in tabella dedicata

            //$table->integer('votes'); //una sorta di like / vero o falso (positivo o negativo)
            //TODO: creare tabella dei like (user_id + resource_user_id + voto positivo o negativo)

            $table->float('lat', 9, 7)->nullable(); //aggiungi il pt in cui ti trovi (opzionale)
            $table->float('lon', 9, 7)->nullable();

            $table->string('note')->nullable();

            $table->index('user_id');
            //$table->index('commentable_id');
            //$table->index('commentable_type');

            $table->foreign('user_id')->references('id')->on('users');


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
        Schema::dropIfExists('evaluations');
    }
}
