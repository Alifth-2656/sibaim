<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\RiwayatIn;
use App\Models\RiwayatMove;
use App\Models\RiwayatOut;
use App\Models\RiwayatSto;
use App\Models\RiwayatStoDetail;
use App\Models\StoDraft;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KelolaBarangController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $aktivitasIn = RiwayatIn::with('barang')->latest()->get()
            ->map(fn($r) => [
                'tipe'       => 'in',
                'nama'       => $r->barang->nama_barang ?? '-',
                'kode'       => $r->barang->kode_barang ?? '-',
                'qty'        => $r->qty,
                'pic'        => $r->pic,
                'created_at' => $r->created_at,
            ]);

        $aktivitasOut = RiwayatOut::with('barang')->latest()->get()
            ->map(fn($r) => [
                'tipe'       => 'out',
                'nama'       => $r->barang->nama_barang ?? '-',
                'kode'       => $r->barang->kode_barang ?? '-',
                'qty'        => $r->qty,
                'pic'        => $r->pic,
                'created_at' => $r->created_at,
            ]);

        $allAktivitas = $aktivitasIn->concat($aktivitasOut)
            ->sortByDesc('created_at')
            ->values();

        $aktivitasPage    = (int) $request->get('aktivitas_page', 1);
        $aktivitasPerPage = 5;
        $aktivitas        = new \Illuminate\Pagination\LengthAwarePaginator(
            $allAktivitas->forPage($aktivitasPage, $aktivitasPerPage),
            $allAktivitas->count(),
            $aktivitasPerPage,
            $aktivitasPage,
            ['path' => $request->url(), 'pageName' => 'aktivitas_page']
        );

        $lowStocks = Barang::whereColumn('qty', '<=', 'min')
            ->orderBy('qty')
            ->paginate(5, ['*'], 'low_page');

        return view('admin.kelola_barang.index', compact('aktivitas', 'lowStocks'));
    }

    // ================= TAMBAH BARANG =================
    public function create()
    {
        return view('admin.kelola_barang.tambah_barang');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barangs,kode_barang',
            'nama_barang' => 'required',
            'satuan'      => 'required',
            'qty'         => 'nullable|integer|min:0',
            'min'         => 'required|integer|min:0',
            'max'         => 'required|integer|min:0|gte:min',
            'pic'         => 'required|string|max:100',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        return DB::transaction(function () use ($request) {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
            }

            $barang = Barang::create([
                'kode_barang' => $request->kode_barang,
                'nama_barang' => $request->nama_barang,
                'satuan'      => $request->satuan,
                'alamat'      => $request->alamat,
                'min'         => $request->min,
                'max'         => $request->max,
                'qty'         => $request->qty ?? 0,
                'image'       => $imagePath
            ]);

            if ($request->qty && $request->qty > 0) {
                RiwayatIn::create([
                    'barang_id'  => $barang->id,
                    'qty'        => $request->qty,
                    'pic'        => $request->pic,
                    'keterangan' => 'Stock awal saat barang dibuat',
                ]);
            }

            return redirect()->route('admin.dashboard')
                ->with('success', 'Barang berhasil ditambahkan');
        });
    }

    // ================= TAMBAH STOK =================
    public function stok()
    {
        $barangs = Barang::all();
        return view('admin.kelola_barang.tambah_stok', compact('barangs'));
    }

    public function storeStok(Request $request)
    {
        $request->validate([
            'pic'   => 'required|string|max:100',
            'items' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->items as $barang_id => $qty) {
                    $barang = Barang::findOrFail($barang_id);
                    $barang->increment('qty', $qty);

                    RiwayatIn::create([
                        'barang_id'  => $barang->id,
                        'qty'        => $qty,
                        'pic'        => $request->pic,
                        'keterangan' => 'Penambahan stok via admin Form',
                    ]);
                }
            });

            return redirect()->route('admin.dashboard')
                ->with('success', 'Semua stok berhasil diperbarui dan tercatat di riwayat.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ================= PINDAH RAK =================
    public function pindah()
    {
        $barangs = Barang::all();
        return view('admin.kelola_barang.pindah_rak', compact('barangs'));
    }

    public function updatePindah(Request $request)
    {
        $request->validate([
            'pic'   => 'required|string|max:100',
            'items' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->items as $barang_id => $data) {
                    $barang = Barang::findOrFail($barang_id);

                    RiwayatMove::create([
                        'barang_id' => $barang->id,
                        'from'      => $data['from'],
                        'to'        => $data['to'],
                        'pic'       => $request->pic,
                    ]);

                    $barang->update(['alamat' => $data['to']]);
                }
            });

            return redirect()->route('admin.dashboard')
                ->with('success', 'Lokasi rak berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memindahkan rak: ' . $e->getMessage());
        }
    }

    // ================= BARANG KELUAR =================
    public function keluar()
    {
        $barangs = Barang::all();
        return view('admin.kelola_barang.barang_keluar', compact('barangs'));
    }

    public function storeKeluar(Request $request)
    {
        $request->validate([
            'pic'   => 'required|string|max:100',
            'items' => 'required|array',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->items as $barang_id => $qty) {
                    $barang = Barang::findOrFail($barang_id);

                    if ($barang->qty < $qty) {
                        throw new \Exception("Stok barang {$barang->nama_barang} tidak mencukupi!");
                    }

                    $barang->decrement('qty', $qty);

                    RiwayatOut::create([
                        'barang_id'  => $barang->id,
                        'qty'        => $qty,
                        'pic'        => $request->pic,
                        'keterangan' => $request->keterangan ?? 'Barang keluar via Form Kelola Barang',
                    ]);
                }
            });

            return redirect()->route('admin.dashboard')
                ->with('success', 'Transaksi barang keluar berhasil diproses.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses barang keluar: ' . $e->getMessage());
        }
    }

    // ================= STO =================
    public function sto()
    {
        $barangs  = Barang::orderBy('kode_barang')->get();
        $stoDraft = StoDraft::where('user_id', Auth::id())->first();
        $prefill  = $stoDraft ? ($stoDraft->items ?? []) : [];

        return view('admin.kelola_barang.sto', compact('barangs', 'stoDraft', 'prefill'));
    }

    public function checkSto(Request $request)
    {
        $request->validate([
            'pic'   => 'required|string|max:100',
            'items' => 'required|array',
        ]);

        $results = [];

        foreach ($request->items as $barangId => $qtyFisik) {
            $barang    = Barang::findOrFail($barangId);
            $qtyFisik  = (int) $qtyFisik;
            $qtySistem = (int) $barang->qty;
            $selisih   = $qtyFisik - $qtySistem;

            $results[] = [
                'barang_id'   => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'satuan'      => $barang->satuan,
                'qty_sistem'  => $qtySistem,
                'qty_fisik'   => $qtyFisik,
                'selisih'     => $selisih,
                'status'      => $selisih === 0 ? 'match' : ($selisih > 0 ? 'surplus' : 'deficit'),
            ];
        }

        StoDraft::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'pic'     => $request->pic,
                'results' => $results,
                'items'   => $request->items,
            ]
        );

        return view('admin.kelola_barang.sto_result', [
            'pic'         => $request->pic,
            'results'     => $results,
            'totalBarang' => Barang::count(),
        ]);
    }

    public function discardStoDraft()
    {
        StoDraft::where('user_id', Auth::id())->delete();
        return redirect()->route('admin.dashboard')
            ->with('success', 'Draft STO dihapus. Silakan mulai STO baru.');
    }

    public function confirmSto(Request $request)
    {
        $draft = StoDraft::where('user_id', Auth::id())->first();

        if (!$draft) {
            return redirect()->route('admin.kelola_barang.sto')
                ->with('error', 'Draft STO tidak ditemukan. Silakan ulangi proses STO.');
        }

        DB::beginTransaction();

        try {
            $results      = $draft->results;
            $adjustItems  = $request->input('adjust', []);
            $totalMatch   = collect($results)->where('status', 'match')->count();
            $totalSelisih = collect($results)->where('status', '!=', 'match')->count();

            $sto = RiwayatSto::create([
                'pic'           => $draft->pic,
                'tanggal'       => now()->toDateString(),
                'total_item'    => count($results),
                'total_match'   => $totalMatch,
                'total_selisih' => $totalSelisih,
            ]);

            foreach ($results as $item) {
                $isAdjusted = in_array($item['barang_id'], array_map('intval', $adjustItems));

                RiwayatStoDetail::create([
                    'riwayat_sto_id' => $sto->id,
                    'barang_id'      => $item['barang_id'],
                    'qty_sistem'     => $item['qty_sistem'],
                    'qty_fisik'      => $item['qty_fisik'],
                    'selisih'        => $item['selisih'],
                    'is_adjusted'    => $isAdjusted,
                ]);

                if ($isAdjusted) {
                    Barang::where('id', $item['barang_id'])
                        ->update(['qty' => $item['qty_fisik']]);
                }
            }

            DB::commit();
            $draft->delete();

            return redirect()->route('admin.dashboard')
                ->with('success', 'STO berhasil disimpan. ' . count($adjustItems) . ' item di-adjust.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan STO: ' . $e->getMessage());
        }
    }
}