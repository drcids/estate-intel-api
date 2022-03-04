<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $authors = [];
        for($i = 0; $i < 3; $i++){
            array_push($authors, $this->faker->name());
        };

        return [
            'name' => $this->faker->company(),
            'isbn' => $this->faker->phoneNumber(),
            'authors' =>  $authors,
            'country' => $this->faker->country(),
            'number_of_pages' => $this->faker->address(),
            'publisher' => $this->faker->name(),
            'release_date' => $this->faker->date('Y-m-d'),
        ];
    }
}
