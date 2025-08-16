<?php

namespace App\Services;

use App\Constant\PayType;
use App\Constant\Status;
use App\Dtos\DebtUsersDTOs;
use App\Dtos\TransactionDetailDTOs;
use App\Dtos\TransactionDTOs;
use App\Models\TrxDetail;
use App\Repositories\PayTypeTransactionRepository;
use App\Repositories\TrxRepository;
use App\Repositories\TrxTroliRepository;
use Illuminate\Support\Facades\DB;

class TrxService
{
    private TransactionDTOs $trxdtos;

    public function __construct(
        public ActorService $actorService,
        public TrxRepository $trxRepository,
        public TrxTroliRepository $trxDetailRepository,
        private TrxContainerService $tcs,
        private PayTypeTransactionRepository $payTypes,
        private DebtUserService $debtService,
        public TransactionDetailService $transactionDetailService,
        public OncardPaymentService $oncardPaymentService
    ) {}

    /**
     * @param TransactionDTOs $transactionDTOs
     * @return self
     * set data transaction
     */
    public function fromDTOs(TransactionDTOs $transactionDTOs): self
    {
        try {
            $trxDtos = $this->tcs->setUp(
                transactionDTOs: $transactionDTOs,
                actorService: $this->actorService,
                pelangganId: $transactionDTOs->getOrders()['pelanggan_id'] ?? null,
            );
            $this->trxdtos = $trxDtos->tDTOs;
            return $this;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * @return TransactionDTOs
     * get data transaction
     */
    public function getDTOs(): TransactionDTOs
    {
        return $this->trxdtos;
    }

    /**
     * @throws \Exception
     * @return self
     * pengecekan validasi transaksi sebelum proses
     */
    public function middle(): self
    {
        try {
            $this->tcs->middle();
            return $this;
        } catch (\Throwable $th) {
            throw new \Exception('trx middle error:' . $th->getMessage());
        }
    }

    /**
     * @throws \Exception 
     * @return TransactionDTOs
     * proses transaksi dan detail transaksi
     */
    public function trxProccessing(): TransactionDTOs
    {
        DB::beginTransaction();
        try {
            $trx = $this->trxRepository
                ->setData($this->trxdtos->toArray())
                ->validate()
                ->create(next: function ($response) {
                    collect($this->trxdtos->getTrxDetail())->map(function (TransactionDetailDTOs $trxDetailsDTOs) use ($response) {
                        try {
                            $trxDetailsDTOs->setTransaksiId($response->id);
                            $trxDetails = $this->trxDetailRepository
                                ->setData($trxDetailsDTOs->toArray())
                                ->validate()
                                ->create();
                            $trxDetailsDTOs->setId($trxDetails->id);
                        } catch (\Throwable $th) {
                            throw new \Exception('trx detail error:' . $th->getMessage());
                        }
                    });
                });

            collect($this->tcs->listStokServiceUpdate)->each(function ($stok) {
                $stok->updateStok();
            });

            $this->trxdtos->setId($trx->id);
            if ($this->payment()) {
                $this->updateSuccessAllTrx();
                DB::commit();
                return $this->trxdtos;
            }
            throw new \Exception("transaksi gagal", 500);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception('trx error:' . $th->getMessage());
        }
    }

    public function trxUpdateStatus($invoiceId)
    {
        $trx = $this->trxRepository->findByInvoiceId($invoiceId);
        $trx->status_id = Status::SUCCESS;
        $trx->save();
    }


    public function updateSuccessAllTrx()
    {
        try {
            $invoiceId = $this->tcs->tDTOs->getInvoiceId();
            $this->trxUpdateStatus($invoiceId);
            $this->transactionDetailService
                ->trxUpdateStatus($invoiceId, Status::SUCCESS);
        } catch (\Throwable $th) {
            throw new \Exception('update trx error:' . $th->getMessage());
        }
    }

    public function payment()
    {
        try {
            $getPayType = $this
                ->payTypes
                ->findById($this->trxdtos->getOrders()['payment_type_id']);
            if ($getPayType->id == PayType::DEBS) {
                $this->debsServicePayment();
            }
            if ($getPayType->id == PayType::ONCARD) {
                $this->oncardPaymentService->setUp(
                    trxDto: $this->trxdtos,
                    buyerActor: $this->tcs->actorDTOs->getGeneralActor()
                );
                if ($this->oncardPaymentService->payment())
                    return true;
            }
            return true;
        } catch (\Throwable $th) {
            throw new \Exception('payment error:' . $th->getMessage());
        }
    }

    public function debsServicePayment()
    {
        try {
            $userDebs = $this->debtService->findUserDebs($this->tcs->actorDTOs->getGeneralActor()->getUserId());
            return $this->debtService
                ->setTransactionDto($this->trxdtos)
                ->fromDTOs(
                    new DebtUsersDTOs(
                        user_debt_id: $userDebs?->getUserDebtId() ?? $this->tcs->actorDTOs->getGeneralActor()->getUserId(),
                        total: ($userDebs?->getTotal() ?? 0) + $this->tcs->tDTOs->getSubTotal()
                    )
                )
                ->createForTrx();
        } catch (\Throwable $th) {
            throw new \Exception('debs service error:' . $th->getMessage());
        }
    }


    public function fackture($invoice)
    {
        $readonly = $this->trxRepository->findByInvoiceNumber(invoiceNumber: $invoice);
        return $readonly;
    }

    // history
    public function history()
    {
        return $this->trxRepository->history($this->actorService);
    }
}
