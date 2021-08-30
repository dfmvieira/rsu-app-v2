<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelevanceZones extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relevance_zones', function (Blueprint $table) {
            $table->id();
            $table->double('latitude1', 16, 13);
            $table->double('longitude1', 16, 13);
            $table->double('latitude2', 16, 13);
            $table->double('longitude2', 16, 13);
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
        Schema::dropIfExists('relevance_zones');
    }
}
