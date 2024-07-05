@extends('Template.layout')
@push('style')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js.map"></script>
<link crossorigin="anonymous" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" referrerpolicy="no-referrer" rel="stylesheet" />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endpush
@push('script')
<Script>
    function openPrintWindow($inv) {
        const printWindow = window.open(`{{ url('trx/faktur') }}/${$inv}`, 'PrintWindow', 'width=800,height=600');
        printWindow.focus();
    }
    $(".prints").click(function() {
        let invoice = $(this).data('invoice');
        openPrintWindow(invoice);
    });
</Script>
@endpush
@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li aria-current="page" class="breadcrumb-item active">History Transaksi</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="example" style="width:100%">
                        <thead>
                            <tr>
                                <td>No</td>
                                <td>Invoice</td>
                                <td>Pelanggan</td>
                                <td>Total Item</td>
                                <td>Total Gross</td>
                                <td>Total Diskon</td>
                                <td>Total Ppn</td>
                                <td>Sub Total</td>
                                <td>Metode Pembayaran</td>
                                <td>#</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trx as $key => $t)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>
                                    <a class="text-sky-500" target="__blank" href="{{ url('trx/history/'.$t->invoice->invoice_id)}} ">{{ $t->invoice->invoice_id }}</a>
                                </td>
                                <td>{{ $t->buyer->nama ?? '' }}</td>
                                <td>{{ $t->transaksiDetail->count() }} Produk</td>
                                <td>{{ 'Rp. ' . number_format($t->total_gross) }}</td>
                                <td>{{ 'Rp. ' . number_format($t->total_diskon) }}</td>
                                <td>{{ 'Rp. ' . number_format($t->tax_deduction) }}</td>
                                <td>{{ 'Rp. ' . number_format($t->sub_total) }}</td>
                                <td>{{ $t->paymentType->alias }}</td>
                                <td>
                                    <button class="btn btn-sm btn-primary prints" data-invoice="{{ $t->invoice->invoice_id }}">
                                        <i class="fa fa-eye text-sm" style="font-size: .9em"></i>
                                    </button>
                                    {{-- hapus --}}
                                    <a class="btn btn-sm btn-danger" href="{{ url('trx/remove', ['invoice' => $t->invoice]) }}">
                                        <i class="fa fa-trash" style="font-size: .9em"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- link --}}
                <div class="d-flex justify-content-center">
                    {{ $trx->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection