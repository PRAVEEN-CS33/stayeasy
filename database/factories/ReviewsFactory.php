<?php

namespace Database\Factories;

use App\Models\Accommodation_details;
use App\Models\reviews;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\reviews>
 */
class ReviewsFactory extends Factory
{
    protected $model = Reviews::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $accommodation = Accommodation_details::all()->first();
        // Log::info($accommodation);

        $user = User::all()->first();

        if (!$accommodation || !$user) {
            return [];
        }
        return [
            'accommodation_id' => $accommodation->accommodation_id,
            'user_id' => User::inRandomOrder()->first()->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'review_text' => $this->faker->paragraph(),
            'review_date' => $this->faker->dateTime()
        ];
    }
}
