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
        DB::table('users')->insert([
            'user_type' => 1,
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'cpf' => '999.999.999-99',
            'password' => Hash::make('testando'),
        ]);
    }
}
