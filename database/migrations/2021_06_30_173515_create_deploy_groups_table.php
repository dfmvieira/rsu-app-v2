<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeployGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deploy_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('notes');
            $table->boolean('status');
            $table->foreignId('ID_sign_group');
            $table->foreignId('ID_user_group');
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
        Schema::dropIfExists('deploy_groups');
    }
}
