<?php

namespace Webkul\BillFiles\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\BillFiles\Models\BillFile;

class BillFileFactory extends Factory
{
    protected $model = BillFile::class;

    public function definition()
    {
        return [
            'billid' => $this->faker->unique()->word,
            'name' => $this->faker->sentence,
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'session' => $this->faker->word,
            'year' => $this->faker->year,
            'is_tracked' => $this->faker->boolean,
        ];
    }
}