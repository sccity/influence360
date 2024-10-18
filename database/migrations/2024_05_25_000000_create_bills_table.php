<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->string('guid', 36)->unique();
            $table->string('tracking_id', 45)->nullable();
            $table->integer('bill_year');
            $table->string('session', 5);
            $table->string('bill_number', 10);
            $table->text('short_title');
            $table->text('general_provisions')->nullable();
            $table->text('highlighted_provisions')->nullable();
            $table->text('subjects')->nullable();
            $table->text('code_sections')->nullable();
            $table->text('appropriations')->nullable();
            $table->text('last_action')->nullable();
            $table->text('last_action_owner')->nullable();
            $table->dateTime('last_action_date')->nullable();
            $table->text('bill_link')->nullable();
            $table->string('sponsor', 255)->nullable();
            $table->string('floor_sponsor', 255)->nullable();
            $table->longText('ai_analysis')->nullable();
            $table->integer('ai_impact_rating')->default(0);
            $table->longText('ai_impact_rating_explanation')->nullable();
            $table->integer('level')->nullable();
            $table->integer('position')->nullable();
            $table->dateTime('date_entered')->nullable();
            $table->boolean('is_tracked')->default(false);
            $table->timestamps();

            $table->unique(['bill_year', 'session', 'bill_number']);
            $table->index(['bill_year', 'session']);
            $table->index('bill_number');
            $table->index('sponsor');
            $table->index('floor_sponsor');
            $table->index('last_action_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bills');
    }
};
