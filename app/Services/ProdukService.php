<?php

namespace App\Services;

use App\Constant\Status;
use App\Dtos\ProdukDTOs;
use App\Models\Produk;
use App\Repositories\ProdukConfigRepository;
use App\Repositories\ProdukRepository;
use Illuminate\Support\Facades\DB;

class ProdukService
{
    private ProdukDTOs $produkDTOs;

    public function __construct(
        public ActorService $actorService,
        public ProdukRepository $produkRepository,
        public ProdukConfigRepository $produkConfigRepository
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
                ->setId($id)
                ->transformer($dto->trasformer())
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
                ->setId($id)
                ->transformer($data)
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
}
