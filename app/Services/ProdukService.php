<?php

namespace App\Services;

use App\Constant\Status;
use App\Dtos\KonversiSatuanDTOs;
use App\Dtos\ProdukDTOs;
use App\Dtos\UnitPriecesDTOs;
use App\Models\Produk;
use App\Repositories\ProdukConfigRepository;
use App\Repositories\ProdukRepository;
use Illuminate\Support\Facades\DB;

class ProdukService
{
    private ProdukDTOs $produkDTOs;
    private $hargaJualProduk;

    public function addOns(array $data)
    {
        $this->hargaJualProduk = $data['harga_jual_produk'];
        return $this;
    }

    public function __construct(
        public ActorService $actorService,
        public ProdukRepository $produkRepository,
        public ProdukConfigRepository $produkConfigRepository,
        public KonversiSatuanService $konversiSatuanService,
        public UnitPriecesService $unitPriecesService
    ) {
    }

    public function fromDTOs(ProdukDTOs $produkDTOs)
    {
        $this->produkDTOs = ProdukDTOs::fromArray(array_merge($produkDTOs->toArray(), [
            'user_id' => $this->actorService->authId(),
            'agency_id' => $this->actorService->agency()->id,
            'gudang_id' => $this->actorService->gudang()->id,
            'status_id' => Status::ACTIVE,
        ]));
        return $this;
    }

    public function create(array $data = []): ProdukDTOs
    {
        DB::beginTransaction();
        try {
            $dto = count($data) > 0 ? (
                ProdukDTOs::fromArray(array_merge($data, [
                    'user_id' => $this->actorService->authId(),
                    'agency_id' => $this->actorService->agency()->id,
                    'gudang_id' => $this->actorService->gudang()->id,
                    'status_id' => Status::ACTIVE,
                ]))
            ) : $this->produkDTOs;
            $prod =  $this->produkRepository->transformer($dto->trasformer())
                ->validate()
                ->create();
            $dto->satuanStok->setProduksId($prod->id);
            $conf = $this->produkConfigRepository
                ->transformer($dto->toArray()['satuan_stok'])
                ->validate()
                ->create();
            if (!$prod) {
                throw new \Exception("Create produk failed");
            }
            // tambahkan unit harga
            $this->unitPriecesService->fromCreatd(UnitPriecesDTOs::fromArray([
                "produks_id" => $prod->id,
                "name" => "pcs", // nama untit harga mmisal 1 renteng dll.
                "priece" => $this->hargaJualProduk, // harga jual
                "jenis_satuan_jual_id" =>  $dto->satuanStok->getSatuanStokId(),
                "diskon" => 0, // diskon 3%
            ]))
                ->store();
            # satuan stok pcs to pcs
            $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
                'produks_id' => $prod->id,
                'satuan_id' => $conf->satuan_stok_id,
                'satuan_konversi_id' => $dto->satuanStok->getSatuanStokId(),
                'nilai_konversi' => (float) 1,
            ]))->create();
            DB::commit();
            return ProdukDTOs::fromArray(collect($prod->toArray())->merge(
                ['satuan_stok' => $conf->toArray()]
            )
                ->toArray())
                ->setId($prod->id);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception($th->getMessage());
        }
    }

    // update produk
    public function update(int $id, array $data): ProdukDTOs
    {
        DB::beginTransaction();
        try {
            $dto = ProdukDTOs::fromArray(array_merge($data, [
                'user_id' => $this->actorService->authId(),
                'agency_id' => $this->actorService->agency()->id,
                'gudang_id' => $this->actorService->gudang()->id,
                'status_id' => Status::ACTIVE,
            ]));

            $created =  $this->produkRepository
                ->transformer($dto->trasformer())
                ->setId($id)
                ->validate()
                ->save();
            $dto->setId($created->id);
            if ($data['satuan_stok']) {
                $dto->satuanStok->setId($created->satuanStok->id);
                $dto->satuanStok->setProduksId($created->id);
                $this->produkConfigRepository
                    ->transformer($dto->satuanStok->toArray())
                    ->validate()
                    ->save();
            }
            if (!$data) {
                throw new \Exception("Update produk failed");
            }
            DB::commit();
            return $dto;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception('prod : ' . $th->getMessage());
        }
    }

    // delete produk
    public function delete(int $id): bool
    {
        // DB::beginTransaction();
        try {
            $readProd = $this->produkRepository->find($id);
            $this->produkConfigRepository
                ->delete($readProd->satuanStok->id);

            $this->produkConfigRepository
                ->removeFromProduksId($id);

            DB::statement('PRAGMA foreign_keys = OFF;');
            DB::table('produks')->where('id', $id)->delete();
            DB::statement('PRAGMA foreign_keys = ON;');
            // DB::commit();
            return true;
        } catch (\Throwable $th) {
            // DB::rollBack();
            throw new \Exception('prod : ' . $th->getMessage());
        }
    }

    // updateSatuanStok
    public function updateSatuanStok(int $id, array $data): Produk
    {
        try {
            $data =  $this->produkConfigRepository
                ->transformer($data)
                ->setId($id)
                ->validate()
                ->save();
            if (!$data) {
                throw new \Exception("Update satuan stok produk failed");
            }
            return $data;
        } catch (\Throwable $th) {
            throw new \Exception('prod : ' . $th->getMessage());
        }
    }

    // get produk gudang
    public function getProdukGudang(): ProdukDTOs
    {
        try {
            $data =  $this->produkRepository
                ->find($this->actorService->gudang()->id);
            if (!$data) {
                throw new \Exception("Get produk gudang failed");
            }
            return new ProdukDTOs($data);
        } catch (\Throwable $th) {
            throw new \Exception('prod : ' . $th->getMessage());
        }
    }

    // getPaginate
    public function getPaginate(int $limit = 10)
    {
        try {
            $data =  $this->produkRepository
                ->getPaginate(function ($q) {
                    return $q->where('agency_id', $this->actorService->agency()->id)
                        ->where("gudang_id", $this->actorService->gudang()->id);
                }, $limit);
            if (!$data) {
                throw new \Exception("Get produk paginate failed");
            }
            return $data;
        } catch (\Throwable $th) {
            throw new \Exception('prod : ' . $th->getMessage());
        }
    }

    public function getProdukById($id)
    {
        return $this->produkRepository->findWhereWith(function ($q) use ($id) {
            return $q->whereId($id);
        }, $this->produkRepository->model::allWith());
    }

    // searchProduk
    public function searchProduk($search, $limit = 10)
    {
        try {
            $this->produkRepository->Inject([
                'unitPriecesRepository' => $this->unitPriecesService->unitPriecesRepository
            ]);
            $this->produkRepository->setActor($this->actorService);
            $data =  $this->produkRepository
                ->searchProduk($search, $limit);
            if (!$data) {
                throw new \Exception("Search produk failed");
            }
            return $data;
        } catch (\Throwable $th) {
            throw new \Exception('prod : ' . $th->getMessage());
        }
    }
}
