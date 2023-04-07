<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id('idresep');
            $table->string('judul');
            $table->string('gambar');
            $table->text('cara_pembuatan');
            $table->string('video');
            $table->string('user_email');
            $table->foreign('user_email')->references('email')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->enum('status_resep', ['draft', 'submit', 'published', 'unpublished'])
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
        Schema::dropIfExists('recipes');
    }
}
