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
                                <li aria-current="page" class="breadcrumb-item active">DATA GUDANG</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div>
                    <a class="btn btn-primary" href="{{ url('gudang/create') }}">
                        <i class="bx bx-plus-circle"></i>
                        Tambah Data
                    </a>

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
                                    <td>Nama Gudang</td>
                                    <td>Alamat</td>
                                    <td>Telepon</td>
                                    <td>Username</td>
                                    <td>#</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($gudang as $key => $g)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $g->nama }}</td>
                                        <td>{{ $g->alamat }}</td>
                                        <td>{{ $g->telepon }}</td>
                                        <td>{{ $g->user->username }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ url('gudang/edit', $g->id) }}">
                                                <i class='bx bx-pencil'></i>
                                                Edit
                                            </a>
                                            <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $g->id }}">
                                                <i class='bx bx-trash'></i>
                                                Delete
                                            </button>
                                        </td>
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
