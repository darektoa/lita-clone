<?php

namespace Database\Seeders;

use App\Models\UsernameException;
use Illuminate\Database\Seeder;

class UsernameExceptionSeeder extends Seeder
{
    public function run()
    {
        $exceptions = [
            'login', 'logout', 'register',
            'banners', 'coins', 'faqs',
            'games', 'genders', 'info',
            'notifications', 'orders', 'profile',
            'posts', 'balances', 'pro',
            'settings', 'user', 'xendit',
        ];

        foreach($exceptions as $item) {
            UsernameException::create([
                'username'  => $item,
            ]);
        }
    }
}
