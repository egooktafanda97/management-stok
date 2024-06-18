<?php

namespace Database\Seeders;

use App\Constant\Status;
use App\Constant\UserType;
use App\Dtos\GeneralActorDTOs;
use App\Dtos\UsersDTOs;
use App\Models\Agency;
use App\Models\GeneralActor;
use App\Models\Kasir;
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
    ) {
    }
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
            "nama" => "Pondok Pesantren Al-Munawwir",
            "username" => "pondok_demo",
            "password" => "password",
            "alamat" => "Agency 1",
        ]);

        // gundang
        auth()->login(User::find(2)); // agency login
        $this->gudangService->create([
            "username" => "gudang_demo",
            "password" => "password",
            "nama" => "Gudang Demo",
            "alamat" => "Gudang 1",
            "telepon" => "08123456789",
            "deskripsi" => "Gudang 1",
        ]);

        // kasir
        auth()->login(User::whereUsername('gudang_demo')->first());
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
        $generalCreated = $this->generalActor->fromDTOs(new GeneralActorDTOs(
            oncard_instansi_id: 1,
            oncard_user_id: 1,
            oncard_account_number: '123123123',
            nama: "ego oktafanda",
            user_type: UserType::Merchant,
            sync_date: Carbon::now()->format("Y-m-d"),
            detail: "user merchant",
            user: new UsersDTOs(
                username: "ego",
                password: "password",
            )
        ))->create();
    }
}
