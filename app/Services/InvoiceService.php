<?php

namespace App\Services;

use App\Dtos\InvoiceDTOs;
use App\Repositories\InvoiceRepository;

class InvoiceService
{
    public InvoiceDTOs $invoiceDTOs;
    public function __construct(
        public ActorService $actorService,
        public InvoiceRepository $invoiceRepository,
    ) {
    }

    public function fromDTOs(InvoiceDTOs $invoice): self
    {
        $invocieplan = $this->invoiceRepository->generatePlan($this->actorService->agency()->kode_instansi);
        $this->invoiceDTOs = $invoice->setInvoiceId($invocieplan);
        return $this;
    }

    public function create(): InvoiceDTOs
    {
        try {
            $created =   $this->invoiceRepository
                ->validate($this->invoiceDTOs->toArray())
                ->create();
            return $this->invoiceDTOs->setId($created->id);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
