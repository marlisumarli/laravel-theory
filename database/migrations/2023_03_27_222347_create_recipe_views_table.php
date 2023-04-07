<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipeViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipe_views', function (Blueprint $table) {
            $table->id('idresep_view');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('resep_idresep');
            $table->foreign('resep_idresep')->references('idresep')->on('recipes')
                ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('recipe_views');
    }
}
