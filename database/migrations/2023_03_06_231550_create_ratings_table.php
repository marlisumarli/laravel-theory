<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id('rating_id');
            $table->integer('rating');
            $table->string('review')->nullable();
            $table->unsignedBigInteger('formula_id');
            $table->foreign('formula_id')->references('formula_id')->on('formulas')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->string('user_email');
            $table->foreign('user_email')->references('email')->on('users')
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
        Schema::dropIfExists('ratings');
    }
}
