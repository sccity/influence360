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
            $table->uuid('guid')->primary();
            $table->string('committee', 255);
            $table->string('committee_id', 10)->nullable();
            $table->dateTime('mtg_time');
            $table->text('mtg_place')->nullable();
            $table->string('link', 255)->nullable();
            $table->dateTime('date_entered');
            $table->dateTime('date_modified')->nullable();
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
