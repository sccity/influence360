<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initiative_pipeline_stages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('probability')->default(0);
            $table->integer('sort_order')->default(0);

            $table->integer('initiative_stage_id')->unsigned();
            $table->foreign('initiative_stage_id')->references('id')->on('initiative_stages')->onDelete('cascade');

            $table->integer('initiative_pipeline_id')->unsigned();
            $table->foreign('initiative_pipeline_id')->references('id')->on('initiative_pipelines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('initiative_pipeline_stages');
    }
};
