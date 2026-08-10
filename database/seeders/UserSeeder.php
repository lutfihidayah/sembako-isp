<?php

namespace Database\Seeders;

use App\Models\DropPoint;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $firstDropPoint = DropPoint::first();

        User::create([
            'name'          => 'Budi Pelanggan',
            'email'         => 'user@sembako.test',
            'password'      => 'password',
            'phone'         => '081234567890',
            'address'       => 'Jl. Soekarno Hatta No. 20, Lowokwaru, Malang',
            'drop_point_id' => $firstDropPoint ? $firstDropPoint->id : null,
        ]);
    }
}
