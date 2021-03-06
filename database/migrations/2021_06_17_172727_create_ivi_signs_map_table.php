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
            $table->foreignId('entityId');
            $table->string('name');
            $table->string('GUID');
            $table->string('viennaSignId');
            $table->double('latitude', 16, 13);
            $table->double('longitude', 16, 13);
            $table->string('comment');
            $table->boolean('locked');
            $table->boolean('deployed');
            $table->boolean('published');
            $table->boolean('madeByFactory')->default(0);
            $table->foreignId('IDDetection');
            $table->foreignId('IDAwareness');
            $table->foreignId('IDRelevance');
            $table->foreignId('IDRSU');
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
        Schema::dropIfExists('ivi_signs_map');
    }
}

            