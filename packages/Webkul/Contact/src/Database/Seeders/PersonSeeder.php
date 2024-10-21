<?php

namespace Webkul\Contact\Database\Seeders;

use Illuminate\Database\Seeder;
use Webkul\Contact\Models\Person;
use Webkul\Contact\Models\Organization;
use Illuminate\Support\Facades\Log;
use Webkul\User\Models\User;

class PersonSeeder extends Seeder
{
    public function run()
    {
        // Find the first user in the system (assuming it's already created)
        $user = User::first();

        if (!$user) {
            Log::error("No user found in the system. Please ensure users are seeded before running PersonSeeder.");
            return;
        }

        // Set the authenticated user for this seeder run
        auth()->login($user);

        $organizations = Organization::all();

        $persons = [
            [
                'name' => 'John Doe',
                'emails' => json_encode([['label' => 'work', 'value' => 'john.doe@example.com']]),
                'contact_numbers' => json_encode([['label' => 'work', 'value' => '123-456-7890']]),
                'job_title' => 'Senator',
                'street' => '123 Main St',
                'city' => 'Washington',
                'state' => 'DC',
                'zip' => '20001',
                'organization_name' => 'US Senate',
            ],
            [
                'name' => 'Jane Smith',
                'emails' => json_encode([['label' => 'work', 'value' => 'jane.smith@example.com']]),
                'contact_numbers' => json_encode([['label' => 'work', 'value' => '987-654-3210']]),
                'job_title' => 'Representative',
                'street' => '456 Elm St',
                'city' => 'Washington',
                'state' => 'DC',
                'zip' => '20002',
                'organization_name' => 'US House of Representatives',
            ],
            [
                'name' => 'Bob Johnson',
                'emails' => json_encode([['label' => 'work', 'value' => 'bob.johnson@example.com']]),
                'contact_numbers' => json_encode([['label' => 'work', 'value' => '555-123-4567']]),
                'job_title' => 'State Senator',
                'street' => '789 Oak St',
                'city' => 'Salt Lake City',
                'state' => 'UT',
                'zip' => '84111',
                'organization_name' => 'UT Senate',
            ],
        ];

        foreach ($persons as $personData) {
            $organizationName = $personData['organization_name'];
            unset($personData['organization_name']);

            $organization = $organizations->where('name', $organizationName)->first();

            if ($organization) {
                $personData['organization_id'] = $organization->id;
                Person::create($personData);
            } else {
                Log::warning("Organization not found: {$organizationName}. Skipping person: {$personData['name']}");
            }
        }
    }
}
