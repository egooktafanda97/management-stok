<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Services\ProdukService;
use Illuminate\Http\Request;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Group;

#[Controllers()]
#[Group(prefix: 'laporan', middleware: [], name: "laporan")]
class LaporanController extends Controller
{
    public function __construct(
        public ProdukService $produkService,
    ) {}

    #[Get("barangmasuk")]
    public function laporanbarangmasuk(Request $request)
    {

        $logBarangMasuks = BarangMasuk::with(BarangMasuk::withAll())
            ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            })
            ->get();
        // return $logBarangMasuks;
        return view('Page.Laporan.barangmasuk', compact('logBarangMasuks'));
    }

    // laporan produk
    #[Get("produk")]
    public function index()
    {
        // Mengambil SEMUA data produk TANPA pagination
        $produk = $this->produkService->getAll();

        return view('Page.Laporan.produk', ['produk' => $produk]);
    }

    #[Get("produk/print")]
    public function printLaporan(Request $request)
    {
        // Mengambil semua data produk tanpa pagination untuk dicetak
        $produk = $this->produkService->getAll();

        return view('Page.Laporan.produk-print', [
            'produk' => $produk,
            'printedAt' => now()->format('d/m/Y H:i:s'),
            'printedBy' => auth()->user()->name ?? 'Administrator'
        ]);
    }

    // #[Get("printbarangmasuk")]
    // public function printbarangmasuk(Request $request)
    // {
    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');

    //     $logBarangMasuks = LogBarangMasuk::with(['user', 'produk', 'supplier', 'satuanBeli', 'satuanStok', 'toko', 'status'])
    //         ->whereBetween('created_at', [$start_date, $end_date])
    //         ->get();

    //     return view('Page.Laporan.printbarangmasuk', compact('logBarangMasuks', 'start_date', 'end_date'));
    // }
    // public function laporanbarangkeluar()
    // {
    //     $detailTransaksis = DetailTransaksi::with(DetailTransaksi::withAll())->get();

    //     return view('page.Laporan.barangkeluar', compact('detailTransaksis'));
    // }
}
