<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            Akun_Kategori_Saldo_Seeder::class
        ]);

        DB::table('users')->insert([
            [
                'username'=>'admin', 
                'nama'=>'admin', 
                'nip'=>'000000001', 
                'password'=>Hash::make('admin'),
            ],
        ]);
    }
}
