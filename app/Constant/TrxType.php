<?php

namespace App\Constant;

enum TrxType: int
{
    const Sale = 1; // Penjualan
    const Purchase = 2; // Pembelian
    const Transfer = 3; // Transfer
    const Withdrawal = 4; // Penarikan
    const Deposit = 5; // Deposit
    const Refund = 6; // Pengembalian
    const Expense = 7; // Pengeluaran
    const Income = 8; // Pemasukan
    const Loan = 9; // Pinjaman
    const TransferBalance = 10; // Transfer Saldo
    const melting = 11;
}
