<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('legislative_calendar', function (Blueprint $table) {
            $table->string('guid', 36)->primary();
            $table->text('description')->nullable();
            $table->string('meeting_id', 50)->nullable();
            $table->string('event_type', 50)->nullable();
            $table->string('link', 255)->nullable();
            $table->string('agenda_url', 255)->nullable();
            $table->string('minutes_url', 255)->nullable();
            $table->string('media_url', 255)->nullable();
            $table->string('emtg_url', 255)->nullable();
            $table->string('ics_url', 255)->nullable();
            $table->date('mtg_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('location', 255)->nullable();
            $table->dateTime('date_entered')->useCurrent();
            $table->dateTime('date_modified')->useCurrentOnUpdate()->nullable();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_bin';
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legislative_calendar');
    }
};
