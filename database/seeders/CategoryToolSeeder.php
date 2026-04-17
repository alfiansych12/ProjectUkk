<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryToolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $multimedia = \App\Models\Kategori::create(['nama_kategori' => 'Multimedia']);
        $pertukangan = \App\Models\Kategori::create(['nama_kategori' => 'Pertukangan']);
        $elektronik = \App\Models\Kategori::create(['nama_kategori' => 'Elektronik']);

        \App\Models\Alat::create([
            'nama_alat' => 'Kamera Canon EOS 80D',
            'spesifikasi' => '24.2MP APS-C CMOS Sensor, DIGIC 6 Image Processor',
            'stok' => 5,
            'harga_sewa_per_hari' => 150000,
            'kategori_id' => $multimedia->id,
        ]);

        \App\Models\Alat::create([
            'nama_alat' => 'Proyektor Epson',
            'spesifikasi' => '3300 Lumens, HDMI, VGA',
            'stok' => 3,
            'harga_sewa_per_hari' => 75000,
            'kategori_id' => $multimedia->id,
        ]);

        \App\Models\Alat::create([
            'nama_alat' => 'Bor Listrik Makita',
            'spesifikasi' => 'High speed, 450W',
            'stok' => 10,
            'harga_sewa_per_hari' => 50000,
            'kategori_id' => $pertukangan->id,
        ]);
    }
}
