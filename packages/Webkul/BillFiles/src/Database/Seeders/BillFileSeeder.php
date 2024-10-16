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
                'status' => 'Active',
                'session' => '2024',
                'year' => 2024,
                'is_tracked' => true,
            ],
            [
                'billid' => 'SB5678',
                'name' => 'Education Funding Bill',
                'status' => 'Pending',
                'session' => '2024',
                'year' => 2024,
                'is_tracked' => false,
            ],
            // Add more sample bill files as needed
        ];

        foreach ($billFiles as $billFile) {
            BillFile::create($billFile);
        }
    }
}