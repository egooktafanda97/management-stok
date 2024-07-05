<?php

namespace App\Services;

use App\Dtos\TransactionDetailDTOs;
use App\Repositories\TrxTroliRepository;

class TransactionDetailService
{
    private TransactionDetailDTOs $transactionDetailDTOs;
    public function __construct(
        public TrxTroliRepository $trxDetailRepository,
        public ActorService $actorService,
    ) {
    }

    public function fromDTOs(TransactionDetailDTOs $transactionDetailDTOs): self
    {
        $this->transactionDetailDTOs = $transactionDetailDTOs;
        return $this;
    }
    public function create(): TransactionDetailDTOs
    {
        try {
            $data = $this->trxDetailRepository
                ->setId($this->transactionDetailDTOs->getId() ?? null)
                ->setData($this->transactionDetailDTOs->toArray())
                ->validate()
                ->save();
            return TransactionDetailDTOs::fromArray($data)->setId($this->transactionDetailDTOs->getId() ?? $data['id']);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function trxUpdateStatus($invoiceId, $statusId)
    {
        try {
            return $this->trxDetailRepository->updateStatusByInvoice($invoiceId, $statusId);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
