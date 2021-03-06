<?php

namespace Database\Factories;

use App\Models\User;
use App\Helpers\UsernameHelper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    
    public function definition()
    {
        $email      = $this->faker->unique()->companyEmail();
        $emailName  = explode('@', $email)[0];
        $gender     = Arr::random(['male', 'female']);

        return [
            'name'              => $this->faker->name($gender),
            'username'          => UsernameHelper::make($emailName),
            'email'             => $email,
            'bio'               => $this->faker->realText(255),
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token'    => Str::random(10),
        ];
    }

    
    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
