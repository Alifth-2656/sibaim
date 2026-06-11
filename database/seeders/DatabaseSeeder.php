<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'username' => 'comodity',
            'password' => Hash::make('123'),
            'role' => 'comodity',
        ]);

        User::create([
            'username' => 'admin',
            'password' => Hash::make('123'),
            'role' => 'admin',
        ]);
        Barang::create([
            'kode_barang' => 'BRG001',
            'nama_barang' => 'Tape Bening',
            'alamat' => 'K. 3.2',
            'image' => 'products/tape_bening.jpeg',
            'qty' => 4,
            'min' => 2,
            'max' => 5,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG002',
            'nama_barang' => 'Kardus Packing',
            'alamat' => 'K. 1.1',
            'image' => 'products/kardus.jpg',
            'qty' => 10,
            'min' => 5,
            'max' => 20,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG003',
            'nama_barang' => 'Lakban Coklat',
            'alamat' => 'K. 2.3',
            'image' => 'products/lakban.jpg',
            'qty' => 1,
            'min' => 3,
            'max' => 10,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG004',
            'nama_barang' => 'Stretching Film',
            'alamat' => 'K. 4.1',
            'image' => 'products/stretching.jpg',
            'qty' => 7,
            'min' => 3,
            'max' => 12,
            'satuan' => 'roll'
        ]);

        Barang::create([
            'kode_barang' => 'BRG005',
            'nama_barang' => 'Plastik Wrapping',
            'alamat' => 'K. 4.2',
            'image' => 'products/plastik.jpg',
            'qty' => 0,
            'min' => 2,
            'max' => 8,
            'satuan' => 'roll'
        ]);

        Barang::create([
            'kode_barang' => 'BRG006',
            'nama_barang' => 'Isolasi Hitam',
            'alamat' => 'K. 3.1',
            'image' => 'products/isolasi.jpg',
            'qty' => 6,
            'min' => 2,
            'max' => 10,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG007',
            'nama_barang' => 'Sarung Tangan',
            'alamat' => 'K. 2.1',
            'image' => 'products/sarung_tangan.jpg',
            'qty' => 3,
            'min' => 5,
            'max' => 15,
            'satuan' => 'box'
        ]);

        Barang::create([
            'kode_barang' => 'BRG008',
            'nama_barang' => 'Masker Medis',
            'alamat' => 'K. 2.2',
            'image' => 'products/masker.jpg',
            'qty' => 20,
            'min' => 10,
            'max' => 50,
            'satuan' => 'box'
        ]);

        Barang::create([
            'kode_barang' => 'BRG009',
            'nama_barang' => 'Pallet Kayu',
            'alamat' => 'Gudang A',
            'image' => 'products/pallet.jpg',
            'qty' => 12,
            'min' => 5,
            'max' => 20,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG010',
            'nama_barang' => 'Spidol Marker',
            'alamat' => 'K. 1.3',
            'image' => 'products/spidol.jpg',
            'qty' => 2,
            'min' => 5,
            'max' => 15,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG011',
            'nama_barang' => 'Lakban Transparan',
            'alamat' => 'K. 2.4',
            'image' => 'products/lakban_transparan.jpg',
            'qty' => 8,
            'min' => 3,
            'max' => 10,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG012',
            'nama_barang' => 'Bubble Wrap',
            'alamat' => 'K. 4.3',
            'image' => 'products/bubble.jpg',
            'qty' => 5,
            'min' => 2,
            'max' => 10,
            'satuan' => 'roll'
        ]);

        Barang::create([
            'kode_barang' => 'BRG013',
            'nama_barang' => 'Kabel Ties',
            'alamat' => 'K. 3.3',
            'image' => 'products/cable_ties.jpg',
            'qty' => 50,
            'min' => 20,
            'max' => 100,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG014',
            'nama_barang' => 'Label Sticker',
            'alamat' => 'K. 1.4',
            'image' => 'products/label.jpg',
            'qty' => 15,
            'min' => 10,
            'max' => 30,
            'satuan' => 'pack'
        ]);

        Barang::create([
            'kode_barang' => 'BRG015',
            'nama_barang' => 'Box Plastik',
            'alamat' => 'Gudang B',
            'image' => 'products/box.jpg',
            'qty' => 6,
            'min' => 3,
            'max' => 15,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG016',
            'nama_barang' => 'Rak Besi',
            'alamat' => 'Gudang A',
            'image' => 'products/rak.jpg',
            'qty' => 2,
            'min' => 1,
            'max' => 5,
            'satuan' => 'unit'
        ]);

        Barang::create([
            'kode_barang' => 'BRG017',
            'nama_barang' => 'Palet Plastik',
            'alamat' => 'Gudang B',
            'image' => 'products/palet_plastik.jpg',
            'qty' => 9,
            'min' => 5,
            'max' => 20,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG018',
            'nama_barang' => 'Stapler Besar',
            'alamat' => 'K. 1.2',
            'image' => 'products/stapler.jpg',
            'qty' => 3,
            'min' => 2,
            'max' => 10,
            'satuan' => 'pcs'
        ]);

        Barang::create([
            'kode_barang' => 'BRG019',
            'nama_barang' => 'Isi Stapler',
            'alamat' => 'K. 1.2',
            'image' => 'products/stapler_isi.jpg',
            'qty' => 30,
            'min' => 10,
            'max' => 50,
            'satuan' => 'box'
        ]);

        Barang::create([
            'kode_barang' => 'BRG020',
            'nama_barang' => 'Gunting Industri',
            'alamat' => 'K. 3.4',
            'image' => 'products/gunting.jpg',
            'qty' => 4,
            'min' => 2,
            'max' => 10,
            'satuan' => 'pcs'
        ]);
    }
}
