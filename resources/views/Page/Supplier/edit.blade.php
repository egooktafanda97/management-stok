@extends('Template.layout')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <h6 class="mb-0 text-uppercase">FORM EDIT SUPPLIER</h6>
            <hr />
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <div>
                        </div>
                    </div>
                    <hr>
                    <form action="{{ url('supplier/editdata/' . $supplier->id) }}" class="row g-3"
                        enctype="multipart/form-data" method="POST">
                        @csrf
                        <!-- Token CSRF -->
                        <div class="col-md-6">
                            <label class="form-label" for="nama_supplier">NAMA SUPPLIER</label>
                            <input class="form-control border-start-0" id="nama_supplier" name="nama_supplier"
                                placeholder="Nama Supplier" required type="text" value="{{ $supplier->name }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="alamat_supplier">ALAMAT SUPPLIER</label>
                            <input class="form-control border-start-0" id="alamat_supplier" name="alamat_supplier"
                                placeholder="Alamat Supplier" required type="text"
                                value="{{ $supplier->alamat_supplier }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="nomor_telepon_supplier">NO HP SUPPLIER</label>
                            <input class="form-control border-start-0" id="nomor_telepon_supplier"
                                name="nomor_telepon_supplier" placeholder="No Hp Supplier" required type="text"
                                value="{{ $supplier->nomor_telepon_supplier }}" />
                        </div>

                        <div class="w-100 border-top"></div>
                        <div class="col">
                            <button class="btn btn-outline-success" type="submit"><i class='bx bx-save me-0'></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
