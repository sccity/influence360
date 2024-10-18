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
        Schema::create('initiative_quotes', function (Blueprint $table) {
            $table->integer('quote_id')->unsigned();
            $table->foreign('quote_id')->references('id')->on('quotes')->onDelete('cascade');

            $table->integer('initiative_id')->unsigned();
            $table->foreign('initiative_id')->references('id')->on('initiatives')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('initiative_quotes');
    }
};
