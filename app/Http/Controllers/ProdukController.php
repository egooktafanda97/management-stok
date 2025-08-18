<?php

namespace App\Http\Controllers;

use App\Dtos\KonversiSatuanDTOs;
use App\Dtos\ProdukDTOs;
use App\Dtos\UnitPriecesDTOs;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\JenisProduk;
use App\Models\Supplier;
use App\Models\Harga;
use App\Models\JenisSatuan;
use App\Models\Rak;
use App\Services\ActorService;
use App\Services\JenisProdukService;
use App\Services\JenisSatuanService;
use App\Services\KonversiSatuanService;
use App\Services\ProdukService;
use App\Services\RakService;
use App\Services\StatusService;
use App\Services\SupplierService;
use App\Services\UnitPriecesService;
use App\Utils\Helpers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Controller\RestController;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;

#[Controllers()]
#[Group(prefix: 'produk', middleware: ['web',  'auth:web', 'role:gudang'], name: 'produk')]
class ProdukController extends Controller
{
    public function __construct(
        public ActorService $actorService,
        public JenisProdukService $jenisProdukService,
        public JenisSatuanService $jenisSatuanService,
        public ProdukService $produkService,
        public SupplierService $supplierService,
        public RakService $rakService,
        public ProdukDTOs $produkDTOs,
        public UnitPriecesService $unitService,
        public KonversiSatuanService $konversiSatuanService,
    ) {}

    #[Get("")]
    public function index()
    {
        // return $this->produkService->getPaginate(2);
        return view('Page.Produk.index', ['produk' => $this->produkService->getPaginate(10)]);
    }


    #[Get("tambah")]
    public function formtambah()
    {
        return view('Page.Produk.tambah', [
            "jenisProduk" => $this->jenisProdukService->getByUsers(),
            "suppliers" => $this->supplierService->getByUsers(),
            "rak" => $this->rakService->getRak(),
            "satuan_jual" => $this->jenisSatuanService->getByAgency(),
        ]);
    }


    #[Get("edit/{id}")]
    public function formEdit($id)
    {
        return view('Page.Produk.edit', [
            "produk" => Produk::find($id),
            "jenisProduk" => $this->jenisProdukService->getByUsers(),
            "suppliers" => $this->supplierService->getByUsers(),
            "rak" => $this->rakService->getRak(),
            "satuan_jual" => $this->jenisSatuanService->getByAgency(),
        ]);
    }


    #[Post("tambahdata")]
    public function store(Request $request)
    {
        try {
            // handelr upload images
            $images = 'default.jpg';
            try {
                $uploaded = Helpers::Images($request, 'gambar', 'imgproduk');
                if ($uploaded->status) {
                    $images = $uploaded->name;
                }
            } catch (\Throwable $th) {
            }
            $prod = $this->produkService->fromDTOs(ProdukDTOs::fromArray([
                'name' => $request->name,
                'deskripsi' => $request->deskripsi,
                'gambar' => $images,
                'jenis_produk_id' => $request->jenis_produk_id,
                'barcode' => $request->barcode,
                'rakId' => $request->rack_id ?? '',
                'satuan_stok' => [
                    'satuan_stok_id' => $request->satuan_id,
                ],
            ]))
                ->addOns(["harga_jual_produk" => $request->harga_jual_produk])
                ->create();
            if ($prod) {
                Alert::success('Success', 'Produk berhasil ditambahkan!');
                return redirect()->route('produk.index');
            } else {
                throw new \Exception('Gagal menyimpan produk.');
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan produk: ' . $e->getMessage());
            return redirect()->route('produk.index');
        }
    }

    #[Get("/addons/{id}")]
    public function addons($id)
    {
        return view('Page.Produk.addons', [
            "id" => $id,
            "produk" => $this->produkService->getProdukById($id),
            "jenisProduk" => $this->jenisProdukService->getByUsers(),
            "suppliers" => $this->supplierService->getByUsers(),
            "rak" => $this->rakService->getRak(),
            "satuanByConversi" => $this->jenisSatuanService->getByNotInUnitPriece(produk_id: $id),
            "satuan" => $this->jenisSatuanService->getByAgency(),
            "unitConvert" => $this->unitService->findByProdukIdPaginate($id),
            "conversion" => $this->konversiSatuanService->getByProduk($id),
        ]);
    }

    #[Post("/addons/{type}/{id?}")]
    public function addonsStore(Request $request, $type, $id = null)
    {
        try {
            if ($type == 'konversi') {
                $converts = $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
                    'produks_id' => $request->produks_id,
                    'satuan_id' => $request->satuan_id,
                    'satuan_konversi_id' =>  $request->satuan_konversi_id,
                    'nilai_konversi' => (float) $request->nilai_konversi,
                ])->setId($id))
                    ->create();
                if ($converts) {
                    Alert::success('Success', 'Konversi satuan berhasil ditambahkan!');
                    return redirect()->back();
                } else {
                    throw new \Exception('Gagal menambahkan konversi satuan.');
                }
            } else {
                $units = $this->unitService->fromCreatd(UnitPriecesDTOs::fromArray([
                    "produks_id" => $request->produks_id,
                    "name" => $request->name,
                    "priece" => $request->harga,
                    "jenis_satuan_jual_id" => $request->satuan_id,
                    "diskon" => $request->diskon
                ])->setId($id))
                    ->store();
                if ($units) {
                    Alert::success('Success', 'Unit harga berhasil ditambahkan!');
                    return redirect('produk/addons/' . $request->produks_id);
                } else {
                    throw new \Exception('Gagal menambahkan unit harga.');
                }
            }
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menambahkan unit: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    #[Get("/addons/delete/{type}/{id}")]
    public function addonsDelete($type, $id)
    {
        try {
            if ($type == 'konversi') {
                $this->konversiSatuanService->delete($id);
            } else {
                $this->unitService->delete($id);
            }
            Alert::success('Success', 'Data berhasil dihapus!');
            return redirect()->back();
        } catch (\Exception $e) {
            Alert::error('Error', 'Gagal menghapus data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    #[Get("search")]
    #[RestController(middleware: ['auth:api'])]
    public function searchProduk()
    {
        try {
            $search = request()->get('search');
            $produk = $this->produkService->searchProduk($search);

            return response()->json($produk);
        } catch (\Throwable $th) {
            return response()->json([]);
        }
    }


    #[Post("editdata/{id}")]
    public function editdata(Request $request, $id)
    {
        // 1. Definisikan aturan validasi langsung di dalam controller.
        //    Ini memastikan semua logika validasi terpusat di sini.
        $rules = [
            'name' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'jenis_produk_id' => 'nullable|exists:jenis_produks,id',
            'barcode' => 'nullable|string|max:100',
            'rak_id' => 'nullable|exists:rak,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'satuan_jual_terkecil_id' => 'nullable|exists:satuan_juals,id',
        ];

        // Jalankan validasi.
        $validatedData = $request->validate($rules);

        try {
            // 2. Cari data produk yang akan diperbarui. Jika tidak ditemukan, akan otomatis menghasilkan 404.
            $produk = Produk::findOrFail($id);

            // 3. Tangani unggahan gambar.
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama jika ada, untuk menghindari file tidak terpakai.
                try {
                    $uploaded = Helpers::Images($request, 'gambar', 'imgproduk');
                    if ($uploaded->status) {
                        $validatedData['gambar'] = $uploaded->name;
                    }
                } catch (\Throwable $th) {
                }
            }

            // 4. Perbarui data produk dengan data yang sudah divalidasi dan aman.
            //    Menggunakan metode fill() untuk mengisi properti yang ada di `$fillable` array model.
            $produk->fill($validatedData);

            // Tambahkan user_id dari user yang sedang login secara langsung di controller.
            // Ini memastikan bahwa `user_id` tidak dapat dimanipulasi dari formulir.
            $produk->user_id = Auth::id();

            // Simpan perubahan ke database.
            $produk->save();

            // 5. Redirect pengguna kembali ke halaman daftar produk dengan pesan sukses.
            return redirect('produk')->with('success', 'Data produk berhasil diperbarui!');
        } catch (\Exception $e) {
            // Tangani kesalahan (misalnya, masalah database)
            return back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    #[Get("hapus/{id}")]
    public function hapus($id)
    {
        $produk = Produk::find($id);

        if (!$produk) {
            return Redirect::back()->withErrors(['Produk tidak ditemukan.']);
        }

        // Hapus gambar dari direktori
        if ($produk->gambar) {
            $gambarPath = public_path($produk->gambar);
            if (file_exists($gambarPath)) {
                unlink($gambarPath);
            }
        }
        $produk->delete();
        Alert::success('Success', 'Produk berhasil dihapus!');
        return redirect()->back();
    }

    #[Get('search/{search}')]
    #[RestController]
    public function getProduk(ProdukService $produkServiecs, Request $request, $search)
    {
        $produk = $produkServiecs->searchProduk($search);

        return response()->json($produk);
    }
}
