<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        $tablePrefix = DB::getTablePrefix();

        Schema::table('initiatives', function (Blueprint $table) {
            $table->integer('initiative_pipeline_stage_id')->after('initiative_pipeline_id')->unsigned()->nullable();
            $table->foreign('initiative_pipeline_stage_id')->references('id')->on('initiative_pipeline_stages')->onDelete('cascade');
        });

        DB::table('initiatives')
            ->update([
                'initiatives.initiative_pipeline_stage_id' => DB::raw($tablePrefix.'initiatives.initiative_stage_id'),
            ]);

        Schema::table('initiatives', function (Blueprint $table) use ($tablePrefix) {
            $table->dropForeign($tablePrefix.'initiatives_initiative_stage_id_foreign');
            $table->dropColumn('initiative_stage_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropForeign(DB::getTablePrefix().'initiatives_initiative_pipeline_stage_id_foreign');
            $table->dropColumn('initiative_pipeline_stage_id');

            $table->integer('initiative_stage_id')->unsigned();
            $table->foreign('initiative_stage_id')->references('id')->on('initiative_stages')->onDelete('cascade');
        });
    }
};
