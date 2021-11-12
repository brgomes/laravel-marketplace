<?php

namespace Database\Seeders;

use App\Store;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*DB::table('users')->insert(
            [
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'email_verified_at' => now(),
                'password' => '123',
                'remember_token' => 'abcdefg',
            ]
        );*/

        factory(User::class, 40)->create()->each(function ($user) {
            $user->store()->save(factory(Store::class)->make());
        });
    }
}
