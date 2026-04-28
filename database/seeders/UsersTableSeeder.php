<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed users with valid schema fields.
     */
    public function run(): void
    {
        DB::table('users')->delete();

        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'id' => 100,
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@demo.com',
                'password' => Hash::make('admin1234'),
                'profile_photo' => null,
                'bio' => 'Site admin',
                'role' => 'admin',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 101,
                'name' => 'Amir Fadli',
                'username' => 'amir',
                'email' => 'amir@demo.com',
                'password' => Hash::make('user1234'),
                'profile_photo' => null,
                'bio' => 'Movie enjoyer',
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 102,
                'name' => 'Nisa Putri',
                'username' => 'nisa',
                'email' => 'nisa@demo.com',
                'password' => Hash::make('user1234'),
                'profile_photo' => null,
                'bio' => 'Sci-fi fan',
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 103,
                'name' => 'Budi Pratama',
                'username' => 'budi',
                'email' => 'budi@demo.com',
                'password' => Hash::make('user1234'),
                'profile_photo' => null,
                'bio' => 'Action and thriller lover',
                'role' => 'user',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}