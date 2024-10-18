<?php

namespace Webkul\Bills\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Bills\Models\Bill;
use Illuminate\Support\Str;

class BillSeeder extends Seeder
{
    public function run()
    {
        $bills = [
            [
                'guid' => Str::uuid()->toString(),
                'tracking_id' => 'HB5678',
                'bill_year' => 2024,
                'session' => '2024',
                'bill_number' => 'HB5678',
                'short_title' => 'Environmental Protection Act',
                'general_provisions' => 'This bill aims to enhance environmental protection measures.',
                'highlighted_provisions' => 'Increases penalties for environmental violations.',
                'subjects' => 'Environment, Conservation',
                'code_sections' => '23-45, 67-89',
                'appropriations' => '$10 million for enforcement',
                'last_action' => 'Passed House Committee',
                'last_action_owner' => 'House Environmental Committee',
                'last_action_date' => now()->subDays(5),
                'bill_link' => 'https://example.com/bills/HB5678',
                'sponsor' => 'John Doe',
                'floor_sponsor' => 'Jane Smith',
                'ai_analysis' => 'This bill has significant positive environmental impact.',
                'ai_impact_rating' => 8,
                'ai_impact_rating_explanation' => 'High impact due to stricter regulations and increased funding.',
                'level' => 2,
                'position' => 1,
                'date_entered' => now()->subDays(30),
                'is_tracked' => true,
            ],
            [
                'guid' => Str::uuid()->toString(),
                'tracking_id' => 'SB9012',
                'bill_year' => 2024,
                'session' => '2024',
                'bill_number' => 'SB9012',
                'short_title' => 'Transportation Infrastructure Bill',
                'general_provisions' => 'This bill focuses on improving state-wide transportation infrastructure.',
                'highlighted_provisions' => 'Allocates funds for road repairs and public transit expansion.',
                'subjects' => 'Transportation, Infrastructure',
                'code_sections' => '34-56, 78-90',
                'appropriations' => '$500 million for infrastructure projects',
                'last_action' => 'Introduced in Senate',
                'last_action_owner' => 'Senate Transportation Committee',
                'last_action_date' => now()->subDays(2),
                'bill_link' => 'https://example.com/bills/SB9012',
                'sponsor' => 'Alice Johnson',
                'floor_sponsor' => 'Bob Williams',
                'ai_analysis' => 'This bill could significantly improve transportation efficiency.',
                'ai_impact_rating' => 7,
                'ai_impact_rating_explanation' => 'High impact due to large-scale infrastructure improvements.',
                'level' => 1,
                'position' => 2,
                'date_entered' => now()->subDays(15),
                'is_tracked' => false,
            ],
        ];

        foreach ($bills as $bill) {
            Bill::create($bill);
        }
    }
}
