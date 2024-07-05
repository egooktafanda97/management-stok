<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;
use TaliumAttributes\Collection\Rutes\Middleware;
use App\Models\LogBarangMasuk;
use App\Models\Pelanggan;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;

#[Controllers()]
#[Group(prefix: 'home', middleware: ['web', 'auth:web'])]
class HomeController extends Controller
{
    #[Get("")]
    public function index()
    {
        return view('Page.Dashboard.index');
    }

    #[Get("testpage")]
    public function testpage()
    {
        return view('Page.TestPage.index');
    }

    #[Get("get/{id}")]
    public function getId($id)
    {
    }

    #[Post("")]
    public function store(Request $request)
    {
    }

    #[Post("{id}")]
    public function update($id)
    {
    }

    #[Post("delete/{id}")]
    public function destory($id)
    {
    }

    // CHART
    public function totalpenjualanchart()
    {
        // Query penjualan per bulan pada tahun ini
        $data_penjualan = Transaksi::selectRaw('MONTH(created_at) as bulan, SUM(total_belanja) as total_bulan')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Inisialisasi array untuk label dan data dengan nilai default
        $labels = [];
        $data = [];

        // Buat array default dengan 12 bulan dari Januari sampai Desember
        for ($i = 1; $i <= 12; $i++) {
            $labels[] = Carbon::createFromFormat('!m', $i)->format('M');
            $data[$i] = 0; // Inisialisasi nilai penjualan menjadi 0
        }

        // Isi data penjualan yang tersedia dari hasil query
        foreach ($data_penjualan as $item) {
            $data[$item->bulan] = $item->total_bulan;
        }

        // Keluarkan data sebagai array untuk JavaScript
        $data_values = array_values($data);

        return response()->json([
            'labels' => $labels,
            'data' => $data_values,
        ]);
    }
}
