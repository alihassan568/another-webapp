<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category' => fake()->randomElement(['Fruits', 'Vegetables', 'Grains', 'Proteins', 'Dairy']),
            'sub_category' => fake()->randomElement(['Apples', 'Oranges', 'Bananas', 'Carrots', 'Rice', 'Wheat', 'Chicken', 'Beef', 'Milk', 'Cheese']),
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'quantity' => fake()->randomNumber(2),
            'price' => fake()->randomFloat(2, 1, 100),
            'image' => fake()->imageUrl(),
            'discount_percentage' => fake()->randomNumber(2),
            'valid_from' => fake()->dateTimeBetween('-1 week', '+1 week')->getTimestamp(),
            'valid_until' => fake()->dateTimeBetween('+1 week', '+2 weeks')->getTimestamp(),
            'pickup_start_time' => fake()->time(),
            'pickup_end_time' => fake()->time(),
            'user_id' => \App\Models\User::factory(),
            'rejection_reason' => fake()->sentence(),
            'commission' => fake()->randomFloat(2, 1, 100),
            'requested_commission' => fake()->randomFloat(2, 1, 100),
            'commission_status' => fake()->randomElement(['pending', 'approved', 'rejected']),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
