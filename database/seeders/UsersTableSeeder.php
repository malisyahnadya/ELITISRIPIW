<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->delete();

        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'id'            => 1,
                'name'          => 'Administrator',
                'username'      => 'admin',
                'email'         => 'admin@demo.com',
                'password'      => Hash::make('admin1234'),
                'profile_photo' => null,
                'bio'           => 'Site administrator.',
                'role'          => 'admin',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 2,
                'name'          => 'Amir Topuria',
                'username'      => 'amir76',
                'email'         => 'amir@demo.com',
                'password'      => Hash::make('user1234'),
                'profile_photo' => null,
                'bio'           => 'Suka film sci-fi dan thriller. Nolan is king.',
                'role'          => 'user',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 3,
                'name'          => 'Nadya Glbk',
                'username'      => 'marviniXZ',
                'email'         => 'nadya@demo.com',
                'password'      => Hash::make('user1234'),
                'profile_photo' => null,
                'bio'           => 'Drama dan horor. Makin disturbing makin bagus.',
                'role'          => 'user',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 4,
                'name'          => 'Yamani',
                'username'      => 'mani123',
                'email'         => 'yamani@demo.com',
                'password'      => Hash::make('user1234'),
                'profile_photo' => null,
                'bio'           => 'Action dan crime lover. Kalau tidak ada ledakan minimal ada twist.',
                'role'          => 'user',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 5,
                'name'          => 'Rama Aporla',
                'username'      => 'rama_aporla',
                'email'         => 'rama@demo.com',
                'password'      => Hash::make('user1234'),
                'profile_photo' => null,
                'bio'           => 'Penonton kasual yang punya opini tidak kasual.',
                'role'          => 'user',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 6,
                'name'          => 'Siska Amelia',
                'username'      => 'siska',
                'email'         => 'siska@demo.com',
                'password'      => Hash::make('user1234'),
                'profile_photo' => null,
                'bio'           => 'Kalau filmnya tidak bikin nangis, berarti belum cukup bagus.',
                'role'          => 'user',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'id'            => 7,
                'name'          => 'Dimas Prakoso',
                'username'      => 'dimas',
                'email'         => 'dimas@demo.com',
                'password'      => Hash::make('user1234'),
                'profile_photo' => null,
                'bio'           => 'Fantasy dan adventure. Hidup terlalu pendek untuk film yang membosankan.',
                'role'          => 'user',
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);
    }
}