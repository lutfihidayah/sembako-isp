<?php

namespace Database\Seeders;

use App\Models\DropPoint;
use Illuminate\Database\Seeder;

class DropPointSeeder extends Seeder
{
    public function run(): void
    {
        $dropPoints = [
            [
                'name'               => 'Warung Pak Budi - Kec. Lowokwaru',
                'address'            => 'Jl. Soekarno Hatta No. 12, Lowokwaru, Malang',
                'region'             => 'Malang Utara',
                'contact_number'     => '081234567890',
                'operational_hours'  => 'Senin-Sabtu 08:00-20:00',
                'is_active'          => true,
            ],
            [
                'name'               => 'Toko Berkah - Kec. Blimbing',
                'address'            => 'Jl. Raya Blimbing No. 45, Blimbing, Malang',
                'region'             => 'Malang Timur',
                'contact_number'     => '081234567891',
                'operational_hours'  => 'Senin-Minggu 07:00-21:00',
                'is_active'          => true,
            ],
            [
                'name'               => 'Minimarket Sejahtera - Kec. Kedungkandang',
                'address'            => 'Jl. Ki Ageng Gribig No. 23, Kedungkandang, Malang',
                'region'             => 'Malang Selatan',
                'contact_number'     => '081234567892',
                'operational_hours'  => 'Senin-Sabtu 08:00-19:00',
                'is_active'          => true,
            ],
            [
                'name'               => 'Kios Bu Dewi - Kec. Sukun',
                'address'            => 'Jl. S. Supriadi No. 67, Sukun, Malang',
                'region'             => 'Malang Barat',
                'contact_number'     => '081234567893',
                'operational_hours'  => 'Senin-Jumat 09:00-18:00',
                'is_active'          => true,
            ],
            [
                'name'               => 'Warung Makmur - Kec. Klojen',
                'address'            => 'Jl. Kawi No. 89, Klojen, Malang',
                'region'             => 'Malang Tengah',
                'contact_number'     => '081234567894',
                'operational_hours'  => 'Senin-Sabtu 08:00-20:00',
                'is_active'          => true,
            ],
            [
                'name'               => 'Toko Barokah - Kec. Dau (Nonaktif)',
                'address'            => 'Jl. Raya Dau No. 10, Dau, Malang',
                'region'             => 'Malang Barat',
                'contact_number'     => '081234567895',
                'operational_hours'  => '-',
                'is_active'          => false,
            ],
        ];

        foreach ($dropPoints as $dp) {
            DropPoint::create($dp);
        }
    }
}
