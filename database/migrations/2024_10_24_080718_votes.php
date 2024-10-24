<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('bill_number', 20)->nullable();
            $table->integer('bill_year')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('action')->nullable();
            $table->text('location')->nullable();
            $table->string('result', 10)->nullable();
            $table->integer('yeas')->nullable();
            $table->integer('nays')->nullable();
            $table->integer('absent')->nullable();
            $table->text('yea_votes')->nullable();
            $table->text('nay_votes')->nullable();
            $table->text('absent_votes')->nullable();
            $table->dateTime('date_entered')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('date_modified')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->unique(['bill_number', 'bill_year', 'date'], 'unique_vote');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
