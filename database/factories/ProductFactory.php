<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(3),
            'price' => fake()->randomFloat(2, 10, 1000),
            'category' => fake()->randomElement(['Electronics', 'Clothing', 'Books', 'Home', 'Sports']),
            'stock' => fake()->numberBetween(0, 100),
            'image' => 'default-product.jpg',
        ];
    }
}
