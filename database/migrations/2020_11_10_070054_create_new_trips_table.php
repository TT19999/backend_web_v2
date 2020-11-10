<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_trips', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('cover');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('duration')->nullable();
            $table->time('departure')->nullable();
            $table->string('price')->nullable();
            $table->string('languages')->nullable();
            $table->string('group-size')->nullable();
            $table->string('categories')->nullable();
            $table->string('transportation')->nullable();
            $table->text('includes')->nullable();
            $table->text('excludes')->nullable();
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
        Schema::dropIfExists('new_trips');
    }
}
