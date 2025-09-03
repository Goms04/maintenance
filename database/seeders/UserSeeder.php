<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'nom' => 'MANOU Gratien',
            'email' => 'manougratien@gmail.com',
            'password' => bcrypt('Gratien04'),
        ]);
    }
}
