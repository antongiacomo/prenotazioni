<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrdinatoRitirato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prenotazioni', function($table) {
            $table->integer('ordinato')->default(0);;
            $table->integer('ritirato')->default(0);;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prenotazioni', function($table) {
            $table->dropColumn('ordinato','ritirato');
        });
    }
}
