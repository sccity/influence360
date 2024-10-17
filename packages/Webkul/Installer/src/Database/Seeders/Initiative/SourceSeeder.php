<?php

namespace Webkul\Installer\Database\Seeders\Initiative;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SourceSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('initiative_sources')->delete();

        $now = Carbon::now();

        $defaultLocale = $parameters['locale'] ?? config('app.locale');

        DB::table('initiative_sources')->insert([
            [
                'id'         => 1,
                'name'       => trans('installer::app.seeders.initiative.source.email', [], $defaultLocale),
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id'         => 2,
                'name'       => trans('installer::app.seeders.initiative.source.web', [], $defaultLocale),
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id'         => 3,
                'name'       => trans('installer::app.seeders.initiative.source.web-form', [], $defaultLocale),
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id'         => 4,
                'name'       => trans('installer::app.seeders.initiative.source.phone', [], $defaultLocale),
                'created_at' => $now,
                'updated_at' => $now,
            ], [
                'id'         => 5,
                'name'       => trans('installer::app.seeders.initiative.source.direct', [], $defaultLocale),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
