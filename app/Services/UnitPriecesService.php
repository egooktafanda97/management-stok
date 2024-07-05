<?php

namespace App\Services;

use App\Constant\Status;
use App\Dtos\UnitPriecesDTOs;
use App\Models\UnitPrieces;
use App\Repositories\ProdukConfigRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\UnitPriecesRepository;

class UnitPriecesService
{
    public UnitPriecesDTOs $unitPriecesDTOs;

    public function __construct(
        public UnitPriecesRepository $unitPriecesRepository,
        public ActorService $actorService,
        public ProdukRepository $produkRepository,
        public ProdukConfigRepository $produkConfigRepository
    ) {
    }

    public function fromCreatd(UnitPriecesDTOs $unitPriecesDTOs): self
    {
        $unitPriecesDTOs
            ->setAgencyId($this->actorService->agency()->id)
            ->setGudangId($this->actorService->gudang()->id)
            ->setUserCreatedId($this->actorService->authId())
            ->setUserUpdateId($this->actorService->authId())
            ->setStatusId(Status::ACTIVE);
        $this->unitPriecesDTOs = $unitPriecesDTOs;
        return $this;
    }

    public function fromUpdate(UnitPriecesDTOs $unitPriecesDTOs): self
    {
        $data = UnitPriecesDTOs::fromArray($unitPriecesDTOs->toArray())->setId($unitPriecesDTOs->id);
        $this->unitPriecesDTOs = $data;
        return $this;
    }

    // store unit prieces
    public function store(): UnitPriecesDTOs
    {
        try {
            $store = $this->unitPriecesRepository
                ->setId($this->unitPriecesDTOs->id ?? null)
                ->validate($this->unitPriecesDTOs->toArray())
                ->save();
            if (!$store) {
                throw new \Exception("Create unit prieces failed");
            }

            return UnitPriecesDTOs::fromArray($store->toArray())->setId($store->id);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
    // update
    public function update()
    {
        try {
            $store = $this->unitPriecesRepository
                ->setId($this->unitPriecesDTOs->id)
                ->validate($this->unitPriecesDTOs->toArray())
                ->save();
            if (!$store) {
                throw new \Exception("Create unit prieces failed");
            }
            return $this->unitPriecesDTOs;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            $delete = $this->unitPriecesRepository
                ->setId($id)
                ->delete();
            if (!$delete) {
                throw new \Exception("Delete unit prieces failed");
            }
            return $delete;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function findByProdukId(int $produkId)
    {
        return $this->unitPriecesRepository->findByProdukId($produkId);
    }

    // findByIdAndProdukId
    public function findByIdSatuanAndProdukId(int $jenisSatuanJualId, int $produkId)
    {
        return $this->unitPriecesRepository->findByIdSatuanAndProdukId($jenisSatuanJualId, $produkId);
    }

    // findByProdukIdPaginate
    public function findByProdukIdPaginate(int $produkId)
    {
        return $this->unitPriecesRepository->findByProdukIdPaginate($produkId);
    }
}
