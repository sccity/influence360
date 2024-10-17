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
        Schema::create('initiative_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity')->default(0);
            $table->decimal('price', 12, 4)->nullable();
            $table->decimal('amount', 12, 4)->nullable();

            $table->integer('initiative_id')->unsigned();
            $table->foreign('initiative_id')->references('id')->on('initiatives')->onDelete('cascade');

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
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
        Schema::dropIfExists('initiative_products');
    }
};
