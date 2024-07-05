<?php

namespace App\Http\Controllers;

use App\Models\Gudang;
use Illuminate\Http\Request;
use App\Models\JenisSatuan;
use App\Services\ActorService;
use App\Services\JenisSatuanService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Name;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;

#[Controllers()]
#[Group(prefix: 'satuan', middleware: ['web',  'auth:web', 'role:gudang'])]
class JenisSatuanController extends Controller
{
    public function __construct(
        public ActorService $actorService,
        public JenisSatuanService $jenisSatuanService
    ) {
    }

    #[Get("")]
    public function index()
    {
        $gudang = Gudang::where('user_id', auth()->user()->id)->first();
        $jenissatuan = JenisSatuan::where("gudang_id", $gudang->id)->get();
        return view('Page.JenisSatuan.index', ['jenissatuan' => $jenissatuan]);
    }


    #[Get("tambah")]
    public function formtambah()
    {
        return view('Page.JenisSatuan.tambah');
    }

    #[Get("edit/{id}")]
    public function editForm($id)
    {
        $jenissatuan = JenisSatuan::find($id);
        if (!$jenissatuan) {
            return redirect()->route('satuan.index')->withErrors(['Jenis Satuan tidak ditemukan.']);
        }
        return view('Page.JenisSatuan.edit', compact('jenissatuan'));
    }


    #[Post("tambahdata")]
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
            ]);
            $jenissatuan = $this->jenisSatuanService->create([
                "name" => $request->nama,
            ]);
            if ($jenissatuan) {
                Alert::success('Success', 'Jenis Satuan berhasil ditambahkan!');
                return redirect()->route('satuan.index');
            } else {
                throw new \Exception('Gagal menyimpan jenis Satuan.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan jenis satuan: ' . $e->getMessage())->withErrors(['Gagal menambahkan jenis satuan: ' . $e->getMessage()]);
        }
    }



    #[Post("editdata/{id}")]

    public function edit(Request $request, $id)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
            ]);
            $jenissatuan = JenisSatuan::find($id);
            if (!$jenissatuan) {
                return redirect()->route('satuan.index')->withErrors(['Jenis Satuan tidak ditemukan.']);
            }
            $jenissatuan->nama = $request->nama;
            $jenissatuan->save();
            Alert::success('Success', 'Jenis Satuan berhasil diubah!');
            return redirect()->route('satuan.index');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengubah jenis satuan: ' . $e->getMessage())->withErrors(['Gagal mengubah jenis satuan: ' . $e->getMessage()]);
        }
    }


    #[Get("hapus/{id}")]
    public function hapus($id)
    {
        $jenissatuan = JenisSatuan::find($id);
        if (!$jenissatuan) {
            return Redirect::back()->withErrors(['Jenis Satuan tidak ditemukan.']);
        }
        // Hapus data produk dari database
        $jenissatuan->delete();
        Alert::success('Success', 'Jenis Satuan berhasil dihapus!');
        return redirect()->back();
    }
}
