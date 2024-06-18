<?php

namespace App\Constant;

enum Status: int
{
    const Pending = 1; // Menunggu
    const Approved = 2; // Disetujui
    const Rejected = 3; // Ditolak
    const Completed = 4; // Selesai
    const Cancelled = 5; // Dibatalkan
    const Processing = 6; // Sedang Diproses
    const OnHold = 7; // Ditahan
    const Refunded = 8; // Dikembalikan
    const Paid = 9; // Dibayar
    const Unpaid = 10; // Belum Dibayar
    const Overdue = 11; // Terlambat
    const Confirmed = 12; // Dikonfirmasi
    const Disputed = 13; // Diperdebatkan
    const Sent = 14; // Dikirim
    const Draft = 15; // Draf
    const Authorized = 16; // Disahkan
    const PartiallyPaid = 17; // Sebagian Dibayar
    const PartiallyRefunded = 18; // Sebagian Dikembalikan
    const Error = 19; // Kesalahan
    const Reviewing = 20; // Sedang Ditinjau
    const debs = 21;
    const ACTIVE = 22;
    const INACTIVE = 23;
    const SUCCESS = 24;
}
