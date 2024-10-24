<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bill_status', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('bill_number', 50)->nullable();
            $table->integer('bill_year')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('action')->nullable();
            $table->text('location')->nullable();
            $table->dateTime('date_entered')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unique(['bill_number', 'bill_year', 'date'], 'unique_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bill_status');
    }
};
