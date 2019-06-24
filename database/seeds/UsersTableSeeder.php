<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new \App\User();
        $user->name = 'JohnDoe';
        $user->email = 'john@example.com';
        $user->password = Hash::make('secret');
        $user->email_verified_at = \Carbon\Carbon::now()->toDateTimeString();
        $user->save();
    }
}
