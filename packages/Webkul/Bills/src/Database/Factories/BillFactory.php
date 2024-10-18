<?php

namespace Webkul\Bills\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Bills\Models\Bill;

class BillFactory extends Factory
{
    protected $model = Bill::class;

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
