<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'        => 'Paket Sembako Hemat A',
                'description' => 'Paket sembako dasar untuk kebutuhan mingguan keluarga kecil. Cocok untuk 2-3 orang.',
                'items'       => ['Beras 5 kg', 'Minyak Goreng 1 L', 'Gula Pasir 1 kg', 'Tepung Terigu 500 gr', 'Telur 10 butir'],
                'price'       => 95000,
                'stock'       => 50,
                'category'    => 'Hemat',
                'is_active'   => true,
            ],
            [
                'name'        => 'Paket Sembako Standar B',
                'description' => 'Paket sembako lengkap untuk kebutuhan mingguan keluarga sedang. Cocok untuk 3-5 orang.',
                'items'       => ['Beras 10 kg', 'Minyak Goreng 2 L', 'Gula Pasir 2 kg', 'Tepung Terigu 1 kg', 'Telur 20 butir', 'Mi Instan 10 bungkus', 'Garam 1 bungkus'],
                'price'       => 185000,
                'stock'       => 40,
                'category'    => 'Standar',
                'is_active'   => true,
            ],
            [
                'name'        => 'Paket Sembako Premium C',
                'description' => 'Paket sembako premium dengan tambahan protein dan bahan masak komplit untuk keluarga besar.',
                'items'       => ['Beras 10 kg', 'Minyak Goreng 2 L', 'Gula Pasir 2 kg', 'Tepung Terigu 1 kg', 'Telur 30 butir', 'Daging Ayam 1 kg', 'Ikan Sarden kaleng 5 pcs', 'Kecap Manis 1 botol', 'Garam 1 bungkus', 'Kopi Sachet 10 pcs'],
                'price'       => 320000,
                'stock'       => 25,
                'category'    => 'Premium',
                'is_active'   => true,
            ],
            [
                'name'        => 'Paket Minyak & Bumbu',
                'description' => 'Paket khusus minyak goreng dan bumbu dapur untuk keperluan memasak sehari-hari.',
                'items'       => ['Minyak Goreng 5 L', 'Bawang Merah 500 gr', 'Bawang Putih 500 gr', 'Cabai Kering 250 gr', 'Kecap Manis 2 botol', 'Saus Tiram 1 botol'],
                'price'       => 150000,
                'stock'       => 30,
                'category'    => 'Bumbu',
                'is_active'   => true,
            ],
            [
                'name'        => 'Paket Beras Berkualitas',
                'description' => 'Paket beras premium varietas pilihan dari petani lokal. Pulen, harum, dan bergizi.',
                'items'       => ['Beras Premium 25 kg'],
                'price'       => 250000,
                'stock'       => 20,
                'category'    => 'Beras',
                'is_active'   => true,
            ],
            [
                'name'        => 'Paket Anak Sehat',
                'description' => 'Paket nutrisi anak dengan susu, telur, dan bahan bergizi untuk tumbuh kembang optimal.',
                'items'       => ['Susu UHT 1 L (6 pcs)', 'Telur 20 butir', 'Madu 250 ml', 'Oat Instan 500 gr', 'Buah Kaleng Campuran 2 pcs', 'Biskuit Bayi 3 pcs'],
                'price'       => 275000,
                'stock'       => 0,
                'category'    => 'Anak',
                'is_active'   => true,
            ],
        ];

        foreach ($packages as $pkg) {
            Package::create($pkg);
        }
    }
}
