<?php

namespace App\Http\Controllers;

use App\Services\ActorService;
use App\Services\GeneralActorService;
use Illuminate\Http\Request;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Group;

#[Controllers()]
#[Group(prefix: 'debs', middleware: ['web',  'auth:web', 'role:gudang,kasir'])]
class DebsController extends Controller
{
    public function __construct(
        public ActorService $actorService,
        public GeneralActorService $generalActorService
    ) {
    }

    #[Get("")]
    public function index()
    {
        return view('Page.Hutang.show', [
            'pelanggans' => $this->generalActorService->getPelanggan()
        ]);
    }
}
