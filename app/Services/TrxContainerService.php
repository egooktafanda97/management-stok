<?php

namespace App\Services;

use App\Constant\MESSAGE;
use App\Constant\PayType;
use App\Constant\Status;
use App\Constant\TrxType;
use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Dtos\ActorDTOs;
use App\Dtos\AgencyDTOs;
use App\Dtos\GeneralActorDTOs;
use App\Dtos\GudangDTOs;
use App\Dtos\InvoiceDTOs;
use App\Dtos\KasirDTos;
use App\Dtos\ProdukDTOs;
use App\Dtos\StokDTOs;
use App\Dtos\TransactionDetailDTOs;
use App\Dtos\TransactionDTOs;
use App\Dtos\UnitPriecesDTOs;
use App\Models\ConfigGudang;
use App\Models\User;
use App\Repositories\ProdukRepository;
use App\Repositories\StokRepository;
use App\Repositories\UnitPriecesRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;

class TrxContainerService
{
    #[Setter] #[Getter]
    public TransactionDTOs $tDTOs;

    /**
     * Stok
     * @stokDTOs array by produk_id
     */
    #[Setter] #[Getter]
    public array $stokDTOsList;

    /**
     * list item detail transaction
     *  @TransactionDetailDTOs
     */
    #[Setter] #[Getter]
    public array $tDDTOs = [];

    #[Setter] #[Getter]
    public DateTime $tanggal;

    public array $listStokServiceUpdate = [];

    public function __construct(
        #[Setter] #[Getter]
        public ActorDTOs $actorDTOs,
        public ProdukRepository $produkRepository,
        public UnitPriecesRepository $unitPriecesRepository,
        public StokRepository $stokRepository,
        public TransactionDetailDTOs $tDetailDTOs,
        public InvoiceService $invoiceService,
        public KonversiSatuanService $konversiSatuanService,
        public StokService $stokService
    ) {
    }


    public function setUp(TransactionDTOs $transactionDTOs, ActorService $actorService, int $pelangganId): TrxContainerService
    {
        try {
            $this->tDTOs = $transactionDTOs;
            $this->tanggal = new DateTime();
            $this->actorDTOs = new $this->actorDTOs(
                userAuth: User::find($actorService->authId()),

                agency: AgencyDTOs::fromModel($actorService->agency())
                    ->setId($actorService->agency()->id),

                gudang: GudangDTOs::fromModel($actorService->gudang())
                    ->setId($actorService->gudang()->id),

                kasir: KasirDTos::fromModel($actorService->kasir())
                    ->setId($actorService->kasir()->id),

                generalActor: GeneralActorDTOs::fromModel($actorService->general($pelangganId))
                    ->setId($actorService->general($pelangganId)->id)
            );
            $this->setInvoce();
            $this->sumTotalOrderGross();
            $this->setTransaction();
            $this->setTroli();
            $this->sumSubTotal();
            return $this;
        } catch (\Throwable $th) {
            log::error('trx container setUp()' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }

    public function setStokUpdate(): void
    {
        try {
            foreach ($this->tDTOs->getItemsOrder() as $item) {
                try {
                    $unitPriece = $item['satuan']; // object unit priece
                    // get stok  konversi ke satuan stok 
                    $stok = $this->stokRepository->findStokByProdukId($item['produks_id']);
                    if ($stok->satuan_id != $unitPriece->jenis_satuan_jual_id) {
                        $konversi =  $this->konversiSatuanService->convertToSatuanStok(
                            produksId: $item['produks_id'],
                            from: $unitPriece->jenis_satuan_jual_id,
                            qty: $item['qty']
                        );
                        $qtyActual = $konversi['konversi'];
                    } else {
                        $qtyActual = $item['qty'];
                    }
                    $newAmount = $stok->jumlah - $qtyActual;
                    $this->listStokServiceUpdate[] = $this->stokService->fromDTOs(new StokDTOs(
                        agency_id: $this->actorDTOs->getAgency()->getId(),
                        gudang_id: $this->actorDTOs->getGudang()->getId(),
                        produks_id: $item['produks_id'],
                        jumlah: $newAmount,
                        satuan_id: $stok->satuan_id,
                        keterangan: "Barang Keluar"
                    ));
                } catch (\Throwable $th) {
                    log::error('trx container setStokUpdate()' . $th->getMessage());
                    throw new \Exception('setStokUpdate:' . ' ' . $item['produks_id'] . ' ' . $th->getMessage());
                }
            }
        } catch (\Throwable $th) {
            Log::error('trx container validateStok()' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }

    public function setInvoce(): void
    {
        try {
            $this->tDTOs->setInvoiceDTOs($this->invoiceService
                ->fromDTOs(new InvoiceDTOs(
                    agency_id: $this->actorDTOs->getAgency()->getId(),
                    gudang_id: $this->actorDTOs->getGudang()->getId(),
                    users_merchant_id: $this->actorDTOs->getGudang()->getUserId(),
                    users_trx_id: $this->actorDTOs->userAuth->id,
                    trx_types_id: TrxType::Sale,
                    payment_type_id: $this->tDTOs->getOrders()['payment_type_id'] ?? PayType::CASH,
                    dates: Carbon::parse($this->tanggal)->format('Y-m-d'),
                    status_id: Status::Pending
                ))->create());
        } catch (\Throwable $th) {
            throw new \Exception('setup invoice error: ' . $th->getMessage());
        }
    }

    /**
     * Total gross order
     * total dari semua item order di kurangi diskon jika ada
     */
    public function sumTotalOrderGross()
    {
        try {
            $total = 0;

            foreach ($this->tDTOs->getItemsOrder() as $item) {

                try {
                    $unitPriece = $item['satuan']; // object unit priece
                    $diskon = $unitPriece->diskon ?? 0; // %
                    $sums = $unitPriece->priece * $item['qty'];
                    $sums = $sums - ($sums * $diskon / 100);
                    $total += $sums;
                } catch (\Throwable $th) {
                    Log::error('trx container sumTotalOrderGross()' . $th->getMessage());
                    throw new \Exception('sumTotalOrderGross:' . ' ' . $item['produks_id'] . ' ' . $th->getMessage());
                }
            }
            $this->tDTOs->setSubTotalOrder($total);
            return $this;
        } catch (\Throwable $th) {
            Log::error('trx container sumTotalOrderGross()' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }

    /**
     * @TransactionDetailDTOs
     * @return self
     */
    public function setTransaction()
    {
        try {
            $this->tDTOs->fromDTOs(
                new $this->tDTOs(
                    agency_id: $this->actorDTOs->getAgency()->getId(),
                    gudang_id: $this->actorDTOs->getGudang()->getId(),
                    kasir_id: $this->actorDTOs->getKasir()->getId(),
                    user_kasir_id: $this->actorDTOs->getKasir()->getUserId(),
                    user_buyer_id: $this->actorDTOs->getGeneralActor()->getUserId(),
                    invoice_id: $this->tDTOs->invoiceDTOs->getId(),
                    tanggal: Carbon::parse($this->tanggal)->format('Y-m-d'),
                    diskon: $this->tDTOs->getOrders()['diskon'] ?? 0,
                    tax: $this->tDTOs->getOrders()['pph'] ? ConfigGudang::get("pph", $this->actorDTOs->getAgency()->getId(),  $this->actorDTOs->getGudang()->getId()) : 0,
                    total_gross: $this->tDTOs->getSubTotalOrder(),
                    sub_total: $this->tDTOs->getSubTotal(), // set after sumTotalOrder
                    payment_type_id: $this->tDTOs->getOrders()['payment_type_id'] ?? PayType::CASH,
                    status_id: Status::Pending
                )
            );
        } catch (\Throwable $th) {
            Log::error('trx container setTransaction()' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }

    /**
     * array @TransactionDetailDTOs
     * @param array $item
     */
    public function setTroli()
    {
        try {
            $items = [];
            foreach ($this->tDTOs->getItemsOrder() as $item) {
                $produk = $this->produkRepository->find($item['produks_id']);
                $unitPriece = $item['satuan'];
                $totalOrder =  $unitPriece->priece * $item['qty'];
                $jmlDiskon = $unitPriece->diskon != 0 ? ($totalOrder * $unitPriece->diskon / 100) : 0;
                $totalDikurangiDiskon = $totalOrder - $jmlDiskon;
                $items[] = new $this->tDetailDTOs(
                    agency_id: $this->actorDTOs->getAgency()->getId(),
                    gudang_id: $this->actorDTOs->getGudang()->getId(),
                    user_kasir_id: $this->actorDTOs->getKasir()->getId(),
                    user_buyer_id: $this->actorDTOs->getGeneralActor()->getId(),
                    invoice_id: $this->tDTOs->invoiceDTOs->getId(), //
                    //transaksi_id: disetting setelah transaksi dilakukan pada trxservice
                    produks_id: $produk->id, //
                    unit_priece_id: $unitPriece->id, //
                    satuan_id: $unitPriece->jenis_satuan_jual_id, //
                    priece: $unitPriece->priece, //
                    priece_decimal: floatval($unitPriece->priece_decimal), //
                    jumlah: $item['qty'], //
                    total: $totalDikurangiDiskon,
                    diskon: $unitPriece->diskon ?? 0,
                    status_id: Status::Pending
                );
            }
            $this->tDTOs->setTrxDetail($items);
            return $this;
        } catch (\Throwable $th) {
            Log::error('trx container setTroli()' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }

    /**
     * sum total sub total
     * total dari semua item order di kurangi diskon jika ada di tambah pph jika ada
     */
    public function sumSubTotal()
    {
        $total = 0;
        $order = $this->tDTOs->getSubTotalOrder();
        $diskon = $this->tDTOs->getOrders()['diskon'] ?? 0;
        $pph = $this->tDTOs->getOrders()['pph'] ? ConfigGudang::get("pph", $this->actorDTOs->getAgency()->getId(),  $this->actorDTOs->getGudang()->getId()) : 0;
        $totalDiskon = ($order * $diskon / 100);
        $total = $order -  $totalDiskon;
        $tax_dection = $total * $pph / 100;
        $total = $total + $tax_dection;
        $this->tDTOs->setTaxDeduction($tax_dection);
        $this->tDTOs->setTotalDiskon($totalDiskon);
        $this->tDTOs->setSubTotal($total);
    }


    // middle functions 
    public function middle(): self
    {
        try {
            if (!$this->validatePaymentCustomer()) {
                throw new \Exception(MESSAGE::PEMBAYARANTIADAKVALID);
            }
            if (!$this->validateStok()) {
                throw new \Exception(MESSAGE::STOKTIDAKCUKUP);
            }
            return $this;
        } catch (\Throwable $th) {
            Log::error('trx container middle(): ' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }
    /**
     * validasi uang pelanggan >= total order
     */
    public function validatePaymentCustomer(): bool
    {
        if ($this->tDTOs->getOrders()['payment_type_id'] == PayType::CASH) {
            return $this->tDTOs->getOrders()['total_customer_money'] >= $this->tDTOs->getSubTotal();
        } else if ($this->tDTOs->getOrders()['payment_type_id'] == PayType::DEBS) {
            return true;
        }
    }
    /**
     * cek stok tersedia atau tidak
     */
    public function validateStok()
    {
        try {
            $total = 0;
            foreach ($this->tDTOs->getItemsOrder() as $item) {
                try {
                    $unitPriece = $item['satuan']; // object unit priece
                    // get stok  konversi ke satuan stok 
                    $stok = $this->stokRepository->findStokByProdukId($item['produks_id']);
                    if ($stok->satuan_id != $unitPriece->jenis_satuan_jual_id) {
                        $konversi =  $this->konversiSatuanService->convertToSatuanStok(
                            produksId: $item['produks_id'],
                            from: $unitPriece->jenis_satuan_jual_id,
                            qty: $item['qty']
                        );
                        $qtyActual = $konversi['konversi'];
                    } else {
                        $qtyActual = $item['qty'];
                    }

                    if ($stok->jumlah < $qtyActual) {
                        return false;
                    }
                    return true;
                } catch (\Throwable $th) {
                    Log::error('trx container validateStok()' . $th->getMessage());
                    throw new \Exception('validateStok:' . ' ' . $item['produks_id'] . ' ' . $th->getMessage());
                }
            }
        } catch (\Throwable $th) {
            Log::error('trx container validateStok()' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }
}
