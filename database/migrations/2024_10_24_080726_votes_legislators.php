<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votes_legislators', function (Blueprint $table) {
            $table->uuid('guid')->primary();
            $table->uuid('vote_guid');
            $table->uuid('legislator_guid');
            $table->string('vote', 10);
            $table->unique(['vote_guid', 'legislator_guid'], 'unique_vote_legislator');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes_legislators');
    }
};
