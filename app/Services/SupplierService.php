<?php

namespace App\Services;

use App\Repositories\SupplierRepository;

class SupplierService
{
    public function __construct(
        public ActorService $actorService,
        public SupplierRepository $supplierRepository,
    ) {
    }


    // getByUsers
    public function getByUsers()
    {
        return $this->supplierRepository->getByUsers($this->actorService);
    }
}
