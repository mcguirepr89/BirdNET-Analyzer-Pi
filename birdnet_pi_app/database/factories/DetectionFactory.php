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
        $scientific_name = ucwords(fake()->word(1)." ".fake()->word(1));
        $common_name = ucwords(fake()->word(1,true)." ".fake()->word(1,true));
        return [
	    'sci_name' => $scientific_name,
	    'com_name' => $common_name,
	    'confidence' => fake()->randomFloat(4,0,1),
	    'latitude' => '46.423',
	    'longitude' => '87.987',
	    'cutoff' => '0.5',
	    'week' => '31',
	    'sensitivity' => '0.5',
	    'overlap' => '0',
	    'file_name' => 'storage/Segments/'.$common_name.'/'.$common_name.'.mp3'
        ];
    }
}
