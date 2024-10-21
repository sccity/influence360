<?php

namespace Webkul\BillFiles\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\BillFiles\Models\BillFile;
use Illuminate\Support\Str;

class BillFileSeeder extends Seeder
{
    public function run()
    {
        $billFiles = [
            [
                'guid' => Str::uuid()->toString(),
                'billid' => 'HB1234',
                'name' => 'Health Care Reform Act',
                'status' => 'In Process',
                'session' => 'GEN',
                'year' => 2024,
                'is_tracked' => true,
                'sponsor' => 'John Smith',
            ],
            [
                'guid' => Str::uuid()->toString(),
                'billid' => 'SB5678',
                'name' => 'Education Funding Bill',
                'status' => 'Abandoned',
                'session' => 'SUMMER',
                'year' => 2024,
                'is_tracked' => false,
                'sponsor' => 'Jane Doe',
            ],
            [
                'guid' => Str::uuid()->toString(),
                'billid' => 'HB9876',
                'name' => 'Environmental Protection Act',
                'status' => 'In Process',
                'session' => '2024',
                'year' => 2024,
                'is_tracked' => true,
                'sponsor' => 'Michael Johnson',
            ],
            // Add more sample bill files as needed
        ];

        foreach ($billFiles as $billFile) {
            BillFile::create($billFile);
        }
    }
}
