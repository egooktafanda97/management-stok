@extends('Template.layout')
@push('style')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js.map"></script>
    <link crossorigin="anonymous" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        referrerpolicy="no-referrer" rel="stylesheet" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="flex justify-between items-center">
                <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                    <div class="breadcrumb-title pe-3">Payment</div>
                    <div class="ps-3">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                                </li>
                                <li aria-current="page" class="breadcrumb-item">DATA GUDANG</li>
                                <li aria-current="page" class="breadcrumb item active">FORM ENTRI GUDANG</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr />
            <div class="card">
                <div class="card-body">
                    <form action="{{ url('gudang/simpan') }}" method="POST">
                        @csrf
                        <input name="id" type="hidden" value="{{ $gudang->id ?? '' }}">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" for="nama_gudang">Nama Gudang</label>
                                <input class="form-control" id="nama_gudang" name="nama" type="text"
                                    value="{{ old('nama_gudang', $gudang->nama ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" for="alamat">Alamat</label>
                                <input class="form-control" id="alamat" name="alamat" type="text"
                                    value="{{ old('alamat', $gudang->alamat ?? '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" for="telepon">Telepon</label>
                                <input class="form-control" id="telepon" name="telepon" type="text"
                                    value="{{ old('telepon', $gudang->telepon ?? '') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" for="username">Username</label>
                                <input autocomplet="" class="form-control" id="username" name="username" type="text"
                                    value="{{ old('username', $gudang->username ?? '') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" for="password">Password</label>
                                <input class="form-control" id="password" name="password" type="password"
                                    value="{{ old('password', $gudang->password ?? '') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold" for="deskripsi">Keterangan *</label>
                                <textarea class="form-control" name="deskripsi" row="3">{{ old('password', $gudang->password ?? '') }}</textarea>
                            </div>
                        </div>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    @endsection
