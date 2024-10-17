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
        Schema::create('initiatives', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('initiative_value', 12, 4)->nullable();
            $table->boolean('status')->nullable();
            $table->text('lost_reason')->nullable();
            $table->datetime('closed_at')->nullable();

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('person_id')->unsigned();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');

            $table->integer('initiative_source_id')->unsigned();
            $table->foreign('initiative_source_id')->references('id')->on('initiative_sources')->onDelete('cascade');

            $table->integer('initiative_type_id')->unsigned();
            $table->foreign('initiative_type_id')->references('id')->on('initiative_types')->onDelete('cascade');

            $table->integer('initiative_pipeline_id')->unsigned()->nullable();
            $table->foreign('initiative_pipeline_id')->references('id')->on('initiative_pipelines')->onDelete('cascade');

            $table->integer('initiative_stage_id')->unsigned();
            $table->foreign('initiative_stage_id')->references('id')->on('initiative_stages')->onDelete('cascade');

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
        Schema::dropIfExists('initiatives');
    }
};
