<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detection>
 */
class DetectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
	    'sci_name' => 'Funk Dobbiest',
	    'com_name' => 'Funky Bird',
	    'confidence' => '.98',
	    'latitude' => '46.423',
	    'longitude' => '87.987',
	    'cutoff' => '0.5',
	    'week' => '31',
	    'sensitivity' => '0.5',
	    'overlap' => '0',
	    'file_name' => fake()->file
        ];
    }
}
