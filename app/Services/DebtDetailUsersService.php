<?php

namespace App\Services;

use App\Dtos\DebtDetailUserDTOs;
use App\Repositories\DebtDetailUsersRepository;

class DebtDetailUsersService
{
    private DebtDetailUserDTOs $debtDetailUserDTOs;

    public function __construct(
        public DebtDetailUsersRepository $debtDetailUsersRepository,
        public ActorService $actorService,
    ) {
    }

    public function fromDTOs(DebtDetailUserDTOs $debtDetailUserDTOs): self
    {
        try {
            $kasir = '';
            try {
                $kasir = $this->actorService->kasir()->id ?? null;
            } catch (\Throwable $th) {
                $kasir = null;
            }
            $this->debtDetailUserDTOs = $debtDetailUserDTOs
                ->setAgencyId($this->actorService->agency()->id)
                ->setGudangId($this->actorService->gudang()->id)
                ->setUserKasirId($kasir ?? null);
            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function createFroTrx()
    {
        try {
            return $this->debtDetailUsersRepository
                ->setId($this->debtDetailUserDTOs->getId() ?? null)
                ->setData($this->debtDetailUserDTOs->toArray())
                ->validate()
                ->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
