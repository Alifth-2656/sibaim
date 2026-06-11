<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_barang', 'like', "%{$search}%");
            });
        }

        // Stats dihitung SEBELUM filter status (biar kartu stats tetap akurat)
        $allBarangs = $query->get();
        $stats = [
            'not_used' => $allBarangs->where('qty', 0)->where('max', 0)->where('min', 0)->count(),
            'kosong'   => $allBarangs->where('qty', 0)->filter(fn($i) => !($i->max == 0 && $i->min == 0))->count(),
            'shortage' => $allBarangs->filter(fn($i) => $i->qty > 0 && $i->qty < $i->min)->count(),
            'over'     => $allBarangs->filter(fn($i) => $i->qty > $i->max)->count(),
            'aman'     => $allBarangs->filter(fn($i) => $i->qty >= $i->min && $i->qty <= $i->max && $i->qty > 0)->count(),
        ];

        // Filter status diterapkan SETELAH stats dihitung
        switch ($request->status) {
            case 'aman':
                $query->where('qty', '>', 0)
                      ->whereColumn('qty', '>=', 'min')
                      ->whereColumn('qty', '<=', 'max');
                break;
            case 'shortage':
                $query->where('qty', '>', 0)
                      ->whereColumn('qty', '<', 'min');
                break;
            case 'kosong':
                $query->where('qty', 0)
                      ->where(fn($q) => $q->where('min', '!=', 0)->orWhere('max', '!=', 0));
                break;
            case 'over':
                $query->whereColumn('qty', '>', 'max');
                break;
            case 'not_used':
                $query->where('qty', 0)->where('min', 0)->where('max', 0);
                break;
        }

        $barangs = $query->orderBy('kode_barang')->paginate(10)->withQueryString();

        return view('admin.inventory.index', compact('barangs', 'stats'));
    }
}