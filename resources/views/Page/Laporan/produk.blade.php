@extends('Template.layout')
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
                            <li aria-current="page" class="breadcrumb-item active">Laporan Data Produk</li>
                        </ol>
                    </nav>
                </div>
                <div class="ms-auto">
                    <div class="btn-group">
                        <a class="btn btn-warning" href="/laporan/" target="_blank">
                            <i class='bx bxs-printer'></i> CETAK LAPORAN
                        </a>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <h6 class="mb-0 text-uppercase">LAPORAN DATA PRODUK</h6>
            <hr />

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="example" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NAMA</th>
                                    <th>JENIS</th>
                                    <th>STOK</th>
                                    <th>HARGA JUAL</th>
                                    <th>DESKRIPSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($produk as $key => $produks)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $produks->name }}</td>
                                        <td>{{ $produks->jenisProduk->name }}</td>
                                        <td>{{ $produks->stok->jumlah ?? 0 }}
                                            {{ $produks->satuanStok->jenisSatuan->name ?? '' }}</td>
                                        <td>
                                            @if (isset($produks->unitPrieces) && count($produks->unitPrieces) > 0)
                                                Rp
                                                {{ number_format($produks->unitPrieces[0]->priece, 0, ',', '.') }}/{{ $produks->unitPrieces[0]->jenisSatuanJual->name }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $produks->deskripsi }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
