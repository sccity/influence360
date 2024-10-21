<?php

namespace Webkul\BillFiles\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\BillFiles\Models\BillFile;

class BillFileSeeder extends Seeder
{
    public function run()
    {
        $billFiles = [
            [
                'billid' => 'HB1234',
                'name' => 'Health Care Reform Act',
                'status' => 'In Process',
                'session' => 'GEN',
                'year' => 2024,
                'is_tracked' => true,
                'sponsor' => 'John Smith',
            ],
            [
                'billid' => 'SB5678',
                'name' => 'Education Funding Bill',
                'status' => 'Abandoned',
                'session' => 'SUMMER',
                'year' => 2024,
                'is_tracked' => false,
                'sponsor' => 'Jane Doe',
            ],
            [
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
