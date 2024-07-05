<?php

namespace App\Services;

use App\Constant\PayType;
use App\Constant\Status;
use App\Constant\TrxType;
use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Contract\AttributesFeature\Utils\AutoAccessor;
use App\Dtos\ActorDTOs;
use App\Dtos\DebtDetailUserDTOs;
use App\Dtos\DebtUsersDTOs;
use App\Dtos\InvoiceDTOs;
use App\Dtos\TransactionDTOs;
use App\Models\User;
use App\Repositories\DebtUserRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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


    public $invoice;

    public function __construct(
        public ActorDTOs $actorDTOs,
        public DebtUserRepository $debtUserRepository,
        public ActorService $actorService,
        private DebtDetailUsersService $debsDetailUsersService,
        public InvoiceService $invoiceService
    ) {
    }


    public function fromDTOs(DebtUsersDTOs $DebtUsersDTOs): self
    {
        $this->DebtUsersDTOs = $DebtUsersDTOs
            ->setAgencyId($this->actorService->agency()->id)
            ->setGudangId($this->actorService->gudang()->id);
        return $this;
    }

    public function createForTrx()
    {
        DB::beginTransaction();
        try {
            $usDebs = $this->debtUserRepository->findWhere(obj: function ($query) {
                return $query->where('user_debt_id', $this->DebtUsersDTOs->getUserDebtId());
            });

            // dd($usDebs, $this->DebtUsersDTOs->getUserDebtId());
            $debs =  $this->debtUserRepository
                ->setId($usDebs->id ?? null)
                ->setData($this->DebtUsersDTOs->toArray())
                ->validate()
                ->save();

            $invoice = null;
            try {
                $invoice = $this->getTransactionDto()->getInvoiceId() ?? null;
            } catch (\Throwable $th) {
                $invoice = null;
            }
            if (empty($invoice)) {
                $invoice = $this->invoice;
            }
            if (empty($invoice))
                throw new \Exception("Invoice tidak ditemukan");

            $results = $this->debsDetailUsersService->fromDTOs(
                new DebtDetailUserDTOs(
                    user_debt_id: $debs->user_debt_id,
                    invoice_id: $invoice,
                    total_hutang: $debs->total,
                    total_bayar: 0,
                    total_sisa: $debs->total,
                    payment_type_id: PayType::DEBS,
                    status_id: Status::Pending
                )
            )->createFroTrx();
            DB::commit();
            return $results;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new \Exception('create debs error: ' . $th->getMessage());
        }
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

    // get user debs getUserDebs
    public function getUserDebs()
    {
        $userDebs = $this->debtUserRepository->getUserDebs($this->actorService);
        return  $userDebs;
    }

    public function setInvoce(array $props): void
    {
        try {
            $invoince =  $this->invoiceService
                ->fromDTOs(new InvoiceDTOs(
                    agency_id: $this->actorService->agency()->id,
                    gudang_id: $this->actorService->gudang()->id,
                    users_merchant_id: $this->actorService->gudang()->user_id,
                    users_trx_id: $props['user_debs_id'],
                    trx_types_id: TrxType::PAYMENT,
                    payment_type_id: $props['payment_type_id'],
                    dates: Carbon::now()->format('Y-m-d'),
                    status_id: Status::Pending
                ))->create();
            $this->invoice = $invoince->id;
        } catch (\Throwable $th) {
            throw new \Exception('setup invoice error: ' . $th->getMessage());
        }
    }

    // get payment debs
    public function payRequest(array $data)
    {

        $debs = DebtUsersDTOs::setUp($data['user_debs_id']);
        $sisaHutang = $debs->total - $data['nominal'];
        $debs->setTotal($sisaHutang);
        $debs->setId($data['user_debs_id']);
        $this->setInvoce([
            'payment_type_id' => $data['metode'],
            "user_debs_id" => $data['user_debs_id']
        ]);
        return $this->fromDTOs($debs)->createForTrx();
    }
}
