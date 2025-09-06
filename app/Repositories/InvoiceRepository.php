<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Invoices;
use Carbon\Carbon;

#[Repository(model: Invoices::class)]
class InvoiceRepository extends BaseRepository
{
    private $invoicePlan;
    private $prefix = 'UN-';

    public function lastInvoice()
    {
        // Ambil invoice terakhir
        $lastInvoice = $this->model::orderBy('invoice_id', 'desc')->first();

        if ($lastInvoice) {
            // Ambil nomor invoice terakhir dan tambahkan 1
            $lastNumber = intval($lastInvoice->invoice_id);
            $newNumber = $lastNumber + 1;

            // Format nomor invoice baru dengan leading zeros
            return str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        } else {
            // Jika belum ada invoice, mulai dari 0001
            return '0001';
        }
    }
    public function generatePlan($append = null, $segments = null)
    {
        $prefix = $this->prefix;
        $currentTime = Carbon::now()->format('ymdhsi');
        $invoiceNumber = $prefix
            . (!empty($append) ? $append : '')
            . '-' . $currentTime
            . ($segments ? '-' . $segments : '-' . $this->lastInvoice());
        $isExists = $this->model::where('invoice_id', $invoiceNumber)->exists();
        // ambil invoce terakhir dan tambahkan 1 degan format 0001
        if ($isExists) {
            return $this->generatePlan($append, $segments);
        }
        $plan = $invoiceNumber;
        $this->invoicePlan = $plan;
        return $plan;
    }

    public function getInvocePlan()
    {
        return $this->invoicePlan;
    }
}
