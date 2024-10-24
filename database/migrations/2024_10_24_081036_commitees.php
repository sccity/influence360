<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->string('id', 50)->unique();
            $table->string('description', 255);
            $table->string('link', 255)->nullable();
            $table->text('meetings')->nullable();
            $table->text('members')->nullable();
            $table->tinyInteger('active')->nullable();
            $table->dateTime('date_entered');
            $table->dateTime('date_modified')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('committees');
    }
};
