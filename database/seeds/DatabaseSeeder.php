<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use \App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // inserted root user
        DB::table('user')->insert([
            'role' => 'operator',
            'firstname' => 'root',
            'lastname' => 'user',
            'email' => config('app.root_username'),
            'password' => Hash::make(config('app.root_password')),
            'phonenumber' => '1112223333',
        ]);
    }
}
