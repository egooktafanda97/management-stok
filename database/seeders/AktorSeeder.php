<?php

namespace Database\Seeders;

use App\Constant\Status;
use App\Constant\UserType;
use App\Dtos\GeneralActorDTOs;
use App\Dtos\UsersDTOs;
use App\Models\Agency;
use App\Models\GeneralActor;
use App\Models\Kasir;
use App\Models\Supplier;
use App\Models\User;
use App\Repositories\GudangRepository;
use App\Repositories\UserRepository;
use App\Services\ActorService;
use App\Services\AgencyService;
use App\Services\GeneralActorService;
use App\Services\GudangService;
use App\Services\KasirService;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AktorSeeder extends Seeder
{
    public function __construct(
        public ActorService $actor,
        public AgencyService $agencyService,
        public GudangService $gudangService,
        public KasirService $kasirService,
        public GeneralActorService $generalActor,
    ) {}
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // super admin
        User::create([
            'nama' => 'Super Admin',
            'username' => 'superadmin',
            'password' => "password",
            'role' => 'superadmin',
            'status_id' => Status::ACTIVE,
        ]);
        // agency create
        $this->agencyService->create([
            "oncard_instansi_id" => 1,
            "kode_instansi" => "AGY1",
            "nama" => "Agency 1",
            "username" => "agency_demo",
            "password" => "password",
            "alamat" => "Agency 1",
        ]);



        // gundang
        $authX =  auth()->login(User::whereUsername('agency_demo')->first());
        $gudangx =  $this->gudangService->create([
            "username" => "toko_demo",
            "password" => "password",
            "nama" => "toko demo",
            "alamat" => "toko_demo 1",
            "telepon" => "08123456789",
            "deskripsi" => "toko demo",
        ]);

        $useAgency = Agency::whereUserId(
            auth()->user()->id
        )->first();


        Supplier::create([
            'agency_id' => $useAgency->id,
            'gudang_id' => $gudangx->id,
            'name' => 'Supplier 1',
            'alamat_supplier' => 'Alamat Supplier 1',
            'nomor_telepon_supplier' => '081234567890',
        ]);
        // kasir
        auth()->login(User::whereUsername('toko_demo')->first());
        $this->kasirService->create([
            "username" => "kasir_demo",
            "password" => "password",
            "nama" => "Kasir Demo",
            "alamat" => "Kasir 1",
            "telepon" => "08123456789",
            "deskripsi" => "Kasir 1",
        ]);


        // general  user
        auth()->login(User::whereUsername('kasir_demo')->first());
        $this->generalActor->fromDTOs(new GeneralActorDTOs(
            oncard_instansi_id: 1,
            oncard_user_id: 1,
            oncard_account_number: '123123123',
            nama: "umum",
            user_type: UserType::Merchant,
            sync_date: Carbon::now()->format("Y-m-d"),
            detail: "user merchant",
            user: new UsersDTOs(
                username: "umum",
                password: "password",
            )
        ))->create();
    }
}
