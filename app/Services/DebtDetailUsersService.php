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
        $this->debtDetailUserDTOs = $debtDetailUserDTOs
            ->setAgencyId($this->actorService->agency()->id)
            ->setGudangId($this->actorService->gudang()->id)
            ->setUserKasirId($this->actorService->kasir()->id);
        return $this;
    }
    public function create()
    {
        return $this->debtDetailUsersRepository
            ->setId($this->debtDetailUserDTOs->getId() ?? null)
            ->setData($this->debtDetailUserDTOs->toArray())
            ->validate()
            ->save();
    }
}
