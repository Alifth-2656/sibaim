<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BarangSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $filePath = database_path('data/data_barang.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error('File tidak ditemukan: ' . $filePath);
            return;
        }

        $rows = \Maatwebsite\Excel\Facades\Excel::toArray([], $filePath)[0];

        if (empty($rows)) {
            $this->command->error('File Excel kosong!');
            return;
        }

        $header   = array_map('trim', $rows[0]);
        $dataRows = array_slice($rows, 1);

        $this->command->info('Total baris ditemukan: ' . count($dataRows));

        $requiredColumns = ['kode_barang', 'nama_barang', 'min'];

        foreach ($requiredColumns as $col) {
            if (!in_array($col, $header)) {
                $this->command->error("Kolom '$col' tidak ditemukan di Excel!");
                return;
            }
        }

        $data = collect($dataRows)
            ->map(fn($row) => array_combine($header, array_pad($row, count($header), null)))
            ->filter(fn($row) => !empty($row['kode_barang']))
            ->map(fn($row) => [
                'kode_barang' => str_pad((string) trim($row['kode_barang']), 6, '0', STR_PAD_LEFT),
                'nama_barang' => trim($row['nama_barang']),
                'alamat'      => '',
                'image'       => 'products/default.jpg',
                'qty'         => 0,
                'min'         => (int) ($row['min'] ?? 0),
                'max'         => 0,
                'satuan'      => 'pcs',
            ])
            ->values();

        $this->command->info('Total barang yang akan diinsert: ' . $data->count());

        $data->chunk(100)->each(fn($chunk) => Barang::insert($chunk->toArray()));

        $this->command->info('Seeder barang selesai! (' . $data->count() . ' data)');

        foreach (
            [
                ['username' => 'comodity', 'role' => 'comodity'],
                ['username' => 'admin',    'role' => 'admin'],
            ] as $user
        ) {
            User::firstOrCreate(
                ['username' => $user['username']],
                ['password' => Hash::make('123'), 'role' => $user['role']]
            );
        }

        $this->command->info('User default siap.');
    }
}
