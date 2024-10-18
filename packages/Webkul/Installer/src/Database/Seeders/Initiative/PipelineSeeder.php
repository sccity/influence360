<?php

namespace Webkul\Installer\Database\Seeders\Initiative;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PipelineSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('initiative_pipelines')->delete();

        DB::table('initiative_pipeline_stages')->delete();

        $now = Carbon::now();

        $defaultLocale = $parameters['locale'] ?? config('app.locale');

        DB::table('initiative_pipelines')->insert([
            [
                'id'         => 1,
                'name'       => trans('installer::app.seeders.initiative.pipeline.default', [], $defaultLocale),
                'is_default' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        DB::table('initiative_pipeline_stages')->insert($data = [
            [
                'id'               => 1,
                'code'             => 'new',
                'name'             => trans('installer::app.seeders.initiative.pipeline.pipeline-stages.new', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 1,
                'initiative_pipeline_id' => 1,
            ], [
                'id'               => 2,
                'code'             => 'follow-up',
                'name'             => trans('installer::app.seeders.initiative.pipeline.pipeline-stages.follow-up', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 2,
                'initiative_pipeline_id' => 1,
            ], [
                'id'               => 3,
                'code'             => 'prospect',
                'name'             => trans('installer::app.seeders.initiative.pipeline.pipeline-stages.prospect', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 3,
                'initiative_pipeline_id' => 1,
            ], [
                'id'               => 4,
                'code'             => 'negotiation',
                'name'             => trans('installer::app.seeders.initiative.pipeline.pipeline-stages.negotiation', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 4,
                'initiative_pipeline_id' => 1,
            ], [
                'id'               => 5,
                'code'             => 'won',
                'name'             => trans('installer::app.seeders.initiative.pipeline.pipeline-stages.won', [], $defaultLocale),
                'probability'      => 100,
                'sort_order'       => 5,
                'initiative_pipeline_id' => 1,
            ], [
                'id'               => 6,
                'code'             => 'lost',
                'name'             => trans('installer::app.seeders.initiative.pipeline.pipeline-stages.lost', [], $defaultLocale),
                'probability'      => 0,
                'sort_order'       => 6,
                'initiative_pipeline_id' => 1,
            ],
        ]);
    }
}