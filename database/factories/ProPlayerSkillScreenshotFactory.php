<?php

namespace Database\Factories;

use App\Models\{ProPlayerSkill, ProPlayerSkillScreenshot};
use Illuminate\Database\Eloquent\Factories\Factory;

class ProPlayerSkillScreenshotFactory extends Factory
{
    protected $model = ProPlayerSkillScreenshot::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $proPlayerSkill = ProPlayerSkill::inRandomOrder()->first();

        return [
            'pro_player_skill_id' => $proPlayerSkill->id,
            'url'                 => 'https://source.unsplash.com/1080x720/?online-game'
        ];
    }
}
