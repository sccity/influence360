<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bill_files', function (Blueprint $table) {
            $table->id();
            $table->string('guid', 36)->unique();
            $table->string('billid');
            $table->string('name');
            $table->string('status');
            $table->string('session');
            $table->year('year');
            $table->boolean('is_tracked');
            $table->string('sponsor', 100);
            $table->unique(['billid', 'year', 'session']);
            $table->timestamps();
        });

        // Check if the activities table exists before creating bill_file_activities
        if (Schema::hasTable('activities')) {
            Schema::create('bill_file_activities', function (Blueprint $table) {
                $table->unsignedBigInteger('activity_id');
                $table->unsignedBigInteger('bill_file_id');
                $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
                $table->foreign('bill_file_id')->references('id')->on('bill_files')->onDelete('cascade');
            });
        } else {
            // Log a warning or throw an exception
            Log::warning('The activities table does not exist. Unable to create bill_file_activities table.');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bill_file_activities');
        Schema::dropIfExists('bill_files');
    }
};
