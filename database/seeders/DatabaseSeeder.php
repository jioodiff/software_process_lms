<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin default
        User::create([
            'nama_lengkap' => 'Admin Lab IPWIJA',
            'nim' => null,
            'email' => 'admin@ipwija.ac.id',
            'password' => 'password',
            'role' => 'admin',
            'program_studi' => null,
            'is_active' => true,
        ]);

        // Generate 20 Mahasiswa
        $programStudis = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen Informatika'];
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'nama_lengkap' => 'mahasiswa' . $i,
                'nim' => (string) (2023000 + $i), // 2023001 to 2023020
                'email' => 'mahasiswa' . $i . '@gmail.com',
                'password' => 'password', // will be hashed by User model / accessor or explicitly (if configured)
                'role' => 'mahasiswa',
                'program_studi' => $programStudis[array_rand($programStudis)],
                'is_active' => true,
            ]);
        }

        // Sample tools
        $tools = [
            ['kode_alat' => 'BB400', 'nama_alat' => 'Breadboard 400 Pin', 'kategori' => 'Komponen', 'stok_total' => 20, 'stok_tersedia' => 20, 'lokasi' => 'Rak A-1'],
            ['kode_alat' => 'ARD-UNO', 'nama_alat' => 'Arduino Uno R3', 'kategori' => 'Microcontroller', 'stok_total' => 15, 'stok_tersedia' => 15, 'lokasi' => 'Rak B-1'],
            ['kode_alat' => 'ESP32', 'nama_alat' => 'ESP32 DevKit V1', 'kategori' => 'Microcontroller', 'stok_total' => 10, 'stok_tersedia' => 10, 'lokasi' => 'Rak B-2'],
            ['kode_alat' => 'DHT22', 'nama_alat' => 'Sensor Suhu & Kelembaban DHT22', 'kategori' => 'Sensor', 'stok_total' => 25, 'stok_tersedia' => 25, 'lokasi' => 'Rak C-1'],
            ['kode_alat' => 'ULTRA-SR04', 'nama_alat' => 'Sensor Ultrasonik HC-SR04', 'kategori' => 'Sensor', 'stok_total' => 18, 'stok_tersedia' => 18, 'lokasi' => 'Rak C-2'],
            ['kode_alat' => 'LCD-1602', 'nama_alat' => 'LCD 16x2 I2C', 'kategori' => 'Display', 'stok_total' => 12, 'stok_tersedia' => 12, 'lokasi' => 'Rak D-1'],
            ['kode_alat' => 'SERVO-MG', 'nama_alat' => 'Servo Motor MG996R', 'kategori' => 'Aktuator', 'stok_total' => 8, 'stok_tersedia' => 8, 'lokasi' => 'Rak E-1'],
            ['kode_alat' => 'RELAY-4CH', 'nama_alat' => 'Relay Module 4 Channel', 'kategori' => 'Modul', 'stok_total' => 10, 'stok_tersedia' => 10, 'lokasi' => 'Rak E-2'],
            ['kode_alat' => 'JMP-WIRE', 'nama_alat' => 'Jumper Wire Set (M-M, M-F, F-F)', 'kategori' => 'Komponen', 'stok_total' => 30, 'stok_tersedia' => 30, 'lokasi' => 'Rak A-2'],
            ['kode_alat' => 'MULTI-DT', 'nama_alat' => 'Multimeter Digital DT830B', 'kategori' => 'Alat Ukur', 'stok_total' => 5, 'stok_tersedia' => 5, 'lokasi' => 'Rak F-1'],
        ];

        foreach ($tools as $tool) {
            Tool::create(array_merge($tool, [
                'status_alat' => 'Tersedia',
                'deskripsi' => 'Alat laboratorium ' . $tool['nama_alat'],
            ]));
        }

        // Sample inventory items
        $items = [
            ['kode_barang' => 'MJ-01', 'nama_barang' => 'Meja Praktikum', 'kategori' => 'Furniture', 'stok' => 20, 'kondisi' => 'Baik', 'lokasi' => 'Lab 1'],
            ['kode_barang' => 'KRS-01', 'nama_barang' => 'Kursi Lab', 'kategori' => 'Furniture', 'stok' => 40, 'kondisi' => 'Baik', 'lokasi' => 'Lab 1'],
            ['kode_barang' => 'PC-01', 'nama_barang' => 'PC Desktop Lab', 'kategori' => 'Komputer', 'stok' => 25, 'kondisi' => 'Baik', 'lokasi' => 'Lab 1'],
            ['kode_barang' => 'SOLD-01', 'nama_barang' => 'Solder Station', 'kategori' => 'Peralatan', 'stok' => 10, 'kondisi' => 'Baik', 'lokasi' => 'Lab 2'],
            ['kode_barang' => 'OSC-01', 'nama_barang' => 'Oscilloscope Digital', 'kategori' => 'Alat Ukur', 'stok' => 3, 'kondisi' => 'Baik', 'lokasi' => 'Lab 2'],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
