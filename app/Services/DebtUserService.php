<?php

namespace App\Services;

use App\Constant\PayType;
use App\Constant\Status;
use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Contract\AttributesFeature\Utils\AutoAccessor;
use App\Dtos\ActorDTOs;
use App\Dtos\DebtDetailUserDTOs;
use App\Dtos\DebtUsersDTOs;
use App\Dtos\TransactionDTOs;
use App\Models\User;
use App\Repositories\DebtUserRepository;

class DebtUserService
{
    use AutoAccessor;
    /**
     * @var DebtUsersDTOs
     * props
     * self(
     *  id: int,
     *  agency_id: int,
     *  gudang_id: int,
     *  user_debt_id: int,
     *  total: int
     * )
     */
    private DebtUsersDTOs $DebtUsersDTOs;

    #[Setter] #[Getter]
    public TransactionDTOs $transaction_dto;

    public function __construct(
        public ActorDTOs $actorDTOs,
        public DebtUserRepository $debtUserRepository,
        public ActorService $actorService,
        private DebtDetailUsersService $debsDetailUsersService
    ) {
    }


    public function fromDTOs(DebtUsersDTOs $DebtUsersDTOs): self
    {
        $this->DebtUsersDTOs = $DebtUsersDTOs
            ->setAgencyId($this->actorService->agency()->id)
            ->setGudangId($this->actorService->gudang()->id);
        return $this;
    }

    public function create()
    {
        $debs =  $this->debtUserRepository
            ->setId($this->DebtUsersDTOs->getId() ?? null)
            ->setData($this->DebtUsersDTOs->toArray())
            ->validate()
            ->save();
        $this->debsDetailUsersService->fromDTOs(
            new DebtDetailUserDTOs(
                user_debt_id: $debs->user_debt_id,
                invoice_id: $this->getTransactionDto()->getInvoiceId(),
                total_hutang: $debs->total,
                total_bayar: 0,
                total_sisa: $debs->total,
                payment_type_id: PayType::DEBS,
                status_id: Status::Pending
            )
        )->create();
    }

    /**
     * @return DebtUsersDTOs
     * get data user berhutang
     */
    public function findUserDebs($userDebs): DebtUsersDTOs | null
    {
        try {
            $isDebs = $this->debtUserRepository->findByUserDebtId($userDebs);
            if ($isDebs) {
                return DebtUsersDTOs::setUp($isDebs->id);
            }
            return null;
        } catch (\Throwable $th) {
            return null;
        }
    }
}
