<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUpdateContributorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('update_contributor', function (Blueprint $table) {
            $table->text('address');
            $table->mediumText('intro');
            $table->string('languages');
            $table->mediumText('experiences');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('update_contributor', function (Blueprint $table) {
            //
        });
    }
}
