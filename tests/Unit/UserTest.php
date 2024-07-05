<?php

namespace Tests\Unit;

use App\Constant\Role;
use App\Constant\Status;
use App\Constant\UserType;
use App\Dtos\AgencyDTOs;
use App\Dtos\GeneralActorDTOs;
use App\Dtos\UsersDTOs;
use App\Models\Agency;
use App\Models\User;
use App\Repositories\AgencyRepository;
use App\Repositories\UserRepository;
use App\Services\ActorService;
use App\Services\AgencyService;
use App\Services\GeneralActorService;
use App\Services\GudangService;
use App\Services\KasirService;
use Carbon\Carbon;
use Database\Seeders\AktorSeeder;
use Database\Seeders\DataMasterSeeder;
use Database\Seeders\ProdukSeeder;
use Database\Seeders\StatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class UserTest extends TestCase
{
    // use WithoutMiddleware;
    use RefreshDatabase;
    protected $agencyService;
    private $agency;
    private $gudang;
    private $kasir;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusSeeder::class);
        $this->agencyService = $this->app->make(AgencyService::class);
    }

    public function Agencystore()
    {
        $data = [
            "oncard_instansi_id" => 1,
            "kode_instansi" => "xAGY1",
            "nama" => "xPondok Pesantren Al-Munawwir",
            "username" => "xpondok_demo",
            "password" => "xpassword",
            "alamat" => "xAgency 1",
        ];
        $createds =  $this->agencyService->create($data);
        $this->agency = $createds;
        return $createds;
    }

    public function testCreateAgency()
    {
        try {

            $service = $this->Agencystore();
            $data = [
                "oncard_instansi_id" => 1,
                "kode_instansi" => "xAGY1",
                "nama" => "xPondok Pesantren Al-Munawwir",
                "username" => "xpondok_demo",
                "password" => "xpassword",
                "alamat" => "xAgency 1",
            ];
            $this->assertNotNull($service);

            $data = [
                "oncard_instansi_id" => 1,
                "kode_instansi" => "xAGY1",
                "nama" => "update Pesantren Al-Munawwir",
                "username" => "xpondok_demo",
                "password" => "xpassword",
                "alamat" => "xAgency 1",
            ];
            $updated = $this->agencyService->update($service['id'], $data);
            $this->assertNotNull($updated);


            $deleted = $this->agencyService->delete($service['id']);
            $this->assertNotNull($deleted);
        } catch (\Throwable $th) {
            $this->fail($th->getMessage());
        }
    }

    public function GudangStore()
    {
        $service = $this->Agencystore();
        auth()->login(User::find($service['user_id']));
        $service = $this->app->make(GudangService::class);
        $data = [
            "username" => "gudang_demo",
            "password" => "password",
            "nama" => "Gudang Demo",
            "alamat" => "Gudang 1",
            "telepon" => "08123456789",
            "deskripsi" => "Gudang 1",
        ];
        $createds =  $service->create($data);
        $this->gudang =  $createds;
        return $createds;
    }

    // test gudang service
    public function testUpdateGudang()
    {
        $service = $this->app->make(GudangService::class);
        $createds =   $this->GudangStore();
        $this->assertNotNull($createds);
        // test update
        $data = [
            "username" => "updae gudang_demo",
            "password" => "password",
            "nama" => "Update Gudang Demo",
            "alamat" => "Gudang 1",
            "telepon" => "08123456789",
            "deskripsi" => "Gudang 1",
        ];
        $updated = $service->update($createds['id'], $data);

        $this->assertNotNull($updated);

        // test delete
        $deleted = $service->delete($createds['id']);
        $this->assertNotNull($deleted);
    }

    public function KasirStore()
    {
        $gudang = $this->GudangStore();
        auth()->login(User::find($gudang['user_id']));
        $service = $this->app->make(KasirService::class);
        $data = [
            "username" => "new_kasir_demo",
            "password" => "password",
            "nama" => "Kasir Demo",
            "alamat" => "Kasir 1",
            "telepon" => "08123456789",
            "deskripsi" => "Kasir 1",
        ];
        return  $service->create($data);
    }

    // test kasir service
    public function testUpdateKasir()
    {

        $kasir = $this->KasirStore();
        $this->assertNotNull($kasir);
        $service = $this->app->make(KasirService::class);
        // // test update
        $data = [
            "username" => "updae kasir_demo",
            "nama" => "Update Kasir Demo",
            "alamat" => "Kasir 1",
            "telepon" => "08123456789",
            "deskripsi" => "Kasir 1",
        ];
        $updated = $service->update($kasir['id'], $data);
        $this->assertNotNull($updated);
        $deleted = $service->delete($kasir['id']);
        $this->assertNotNull($deleted);
    }

    // test user general buyer

    public function testGenral()
    {
        $users = $this->Agencystore();
        auth()->login(User::find($users['user_id']));
        $service = $this->app->make(GeneralActorService::class);
        $generalCreated = $service->fromDTOs(new GeneralActorDTOs(
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
        // expect new created
        $this->assertNotNull($generalCreated);
        $this->assertEquals($generalCreated->getNama(), "ego oktafanda");
        // expect update
        $general = $service->fromDTOs((new GeneralActorDTOs(
            oncard_instansi_id: 1,
            oncard_user_id: 1,
            oncard_account_number: '123123123',
            nama: "update ego oktafanda",
            user_type: UserType::Merchant,
            sync_date: Carbon::now()->format("Y-m-d"),
            detail: "user merchant"
        ))->setId(1))
            ->create();
        $this->assertNotNull($general);
        $this->assertEquals($general->getId(), $generalCreated->getId());
        $this->assertEquals($general->getNama(), "update ego oktafanda");
    }
}
