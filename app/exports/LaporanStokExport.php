<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanStokExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $status;
    protected $search;

    public function __construct($search = null, $status = null)
    {
        $this->search = $search;
        $this->status = $status;
    }

    public function collection()
    {
        $query = Barang::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama_barang', 'like', '%' . $this->search . '%')
                  ->orWhere('kode_barang', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->status === 'aman') {
            $query->whereColumn('qty', '>=', 'min')->whereColumn('qty', '<=', 'max')->where('qty', '>', 0);
        } elseif ($this->status === 'kurang') {
            $query->whereColumn('qty', '<', 'min')->where('qty', '>', 0);
        } elseif ($this->status === 'habis') {
            $query->where('qty', 0);
        }

        return $query->orderBy('kode_barang')->get()->map(function ($item, $i) {
            if ($item->qty == 0) {
                $status = 'Habis';
            } elseif ($item->qty < $item->min) {
                $status = 'Kurang';
            } elseif ($item->qty > $item->max) {
                $status = 'Over';
            } else {
                $status = 'Aman';
            }

            return [
                'No'           => $i + 1,
                'Kode Barang'  => $item->kode_barang,
                'Nama Barang'  => $item->nama_barang,
                'Satuan'       => $item->satuan,
                'Min'          => $item->min,
                'Max'          => $item->max,
                'Stok'         => $item->qty,
                'Status'       => $status,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kode Barang', 'Nama Barang', 'Satuan', 'Min', 'Max', 'Stok', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF1E4D9C']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 30,
            'D' => 10,
            'E' => 8,
            'F' => 8,
            'G' => 8,
            'H' => 12,
        ];
    }
}