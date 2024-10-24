<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('legislators', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('id', 50)->unique();
            $table->string('full_name', 255);
            $table->string('format_name', 255)->nullable();
            $table->string('party', 25)->nullable();
            $table->string('position', 100)->nullable();
            $table->string('district', 25)->nullable();
            $table->string('house', 25)->nullable();
            $table->text('address')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('cell', 20)->nullable();
            $table->string('work_phone', 20)->nullable();
            $table->string('service_start', 100)->nullable();
            $table->text('profession')->nullable();
            $table->text('professional_affiliations')->nullable();
            $table->text('education')->nullable();
            $table->text('recognitions_and_honors')->nullable();
            $table->string('counties', 255)->nullable();
            $table->string('legislation_url', 255)->nullable();
            $table->string('demographic_url', 255)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->text('committees')->nullable();
            $table->text('finance_report')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->dateTime('date_entered');
            $table->dateTime('date_modified')->nullable();
            $table->unique(['guid'], 'UNIQUE');
            $table->unique(['id'], 'UNIQUE_ID');
        });
    }

    public function down()
    {
        Schema::dropIfExists('legislators');
    }
};
