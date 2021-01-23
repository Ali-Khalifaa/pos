<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name'=>'super',
            'last_name'=>'super',
            'email'=>'super_admin@app.com',
            'password'=> bcrypt('secret'),

        ]);
        $user ->attachRole('super_admin');
       
    }
}
