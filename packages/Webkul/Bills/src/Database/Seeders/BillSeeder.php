<?php

namespace Webkul\Bills\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Bills\Models\Bill;

class BillSeeder extends Seeder
{
    public function run()
    {
        $bills = [
            [
                'billid' => 'HB5678',
                'name' => 'Environmental Protection Act',
                'status' => 'Active',
                'session' => '2024',
                'year' => 2024,
                'is_tracked' => true,
            ],
            [
                'billid' => 'SB9012',
                'name' => 'Transportation Infrastructure Bill',
                'status' => 'Pending',
                'session' => '2024',
                'year' => 2024,
                'is_tracked' => false,
            ],
            // Add more sample bills as needed
        ];

        foreach ($bills as $bill) {
            Bill::create($bill);
        }
    }
}
