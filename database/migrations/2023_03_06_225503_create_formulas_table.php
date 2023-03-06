<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormulasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formulas', function (Blueprint $table) {
            $table->id('formula_id');
            $table->string('name');
            $table->text('description');
            $table->string('image');
            $table->string('video');
            $table->string('user_email');
            $table->foreign('user_email')->references('email')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status', ['draft', 'submit', 'published', 'unpublished'])
                ->default('draft');
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
        Schema::dropIfExists('formulas');
    }
}
