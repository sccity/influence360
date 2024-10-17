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

        Schema::table('initiative_pipeline_stages', function (Blueprint $table) {
            $table->string('code')->after('id')->nullable();
            $table->string('name')->after('code')->nullable();
        });

        DB::table('initiative_pipeline_stages')
            ->join('initiative_stages', 'initiative_pipeline_stages.initiative_stage_id', '=', 'initiative_stages.id')
            ->update([
                'initiative_pipeline_stages.code' => DB::raw($tablePrefix.'initiative_stages.code'),
                'initiative_pipeline_stages.name' => DB::raw($tablePrefix.'initiative_stages.name'),
            ]);

        Schema::table('initiative_pipeline_stages', function (Blueprint $table) use ($tablePrefix) {
            $table->dropForeign($tablePrefix.'initiative_pipeline_stages_initiative_stage_id_foreign');
            $table->dropColumn('initiative_stage_id');

            $table->unique(['code', 'initiative_pipeline_id']);
            $table->unique(['name', 'initiative_pipeline_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('initiative_pipeline_stages', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->dropColumn('name');

            $table->integer('initiative_stage_id')->unsigned();
            $table->foreign('initiative_stage_id')->references('id')->on('initiative_stages')->onDelete('cascade');

            $table->dropUnique(['initiative_pipeline_stages_code_initiative_pipeline_id_unique', 'initiative_pipeline_stages_name_initiative_pipeline_id_unique']);
        });
    }
};
