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
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropForeign(['initiative_pipeline_stage_id']);

            $table->foreign('initiative_pipeline_stage_id')->references('id')->on('initiative_pipeline_stages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropForeign(['initiative_pipeline_stage_id']);

            $table->foreign('initiative_pipeline_stage_id')->references('id')->on('initiative_pipeline_stages')->onDelete('cascade');
        });
    }
};
