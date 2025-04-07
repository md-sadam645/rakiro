<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder

{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           'fname'=>Str::random(10),
           'lname'=>Str::random(10),
            'name' => Str::random(10),
            'email' => 'admin@gmail.com',
            'mobile'=>'8817408300',
            'password' => Hash::make('sid123'),
            'photo'=> 'avtaar.jpg',
            'role'=>'1',
            'status'=>'1',
        ]);
        // DB::table('users')->insert([
        //     'fname'=>Str::random(10),
        //     'lname'=>Str::random(10),
        //     'name' => Str::random(10),
        //     'email' => 'hub@gmail.com',
        //     'mobile'=>'8817408301',
        //     'password' => Hash::make('sid123'),
        //     'role'=>'2',
        //     'status'=>'1',
        // ]);
        // DB::table('users')->insert([
        //     'fname'=>Str::random(10),
        //     'lname'=>Str::random(10),
        //     'name' => Str::random(10),
        //     'email' => 'customer@gmail.com',
        //     'mobile'=>'8817408302',
        //     'password' => Hash::make('sid123'),
        //     'role'=>'3',
        //     'status'=>'1',
        // ]);
    }
}
