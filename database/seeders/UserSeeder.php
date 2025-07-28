<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        User::create([
            'nama' => 'Deru',
            'username' => 'deru',
            'role' => 'ADMIN',
            'password' => 'deru123',
        ]);

        User::create([
            'nama' => 'Rey',
            'username' => 'rey',
            'role' => 'KASIR',
            'password' => 'rey123',
        ]);
    }
}
