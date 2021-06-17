<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIviSignsMapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ivi_signs_map', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('GUID');
            $table->foreignId('viennaID');
            $table->double('latitude', 15, 13);
            $table->double('longitude', 15, 13);
            $table->boolean('status');
            $table->string('comment');
            $table->foreignId('locationID');
            $table->foreignId('envelopeIVI');
            $table->foreignId('IVIMID');
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
        Schema::dropIfExists('ivi_sign_maps');
    }
}
