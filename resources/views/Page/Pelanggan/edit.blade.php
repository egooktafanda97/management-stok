@extends('Template.layout')
@push('style')
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.2/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="flex items-center">
                {{-- back --}}
                <a aria-selected="true" href="{{ url('pelanggan') }}">
                    <div class="flex justify-center h-full align-items-center w-[30px]">
                        {{-- back --}}
                        <div class="tab-icon text-lg"><i class="bx bx-arrow-to-left"></i></div>
                    </div>
                </a>
                <h6 class="mb-0 text-uppercase">FORM EDIT PELANGGAN</h6>
            </div>

            <hr />
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <div>
                        </div>
                    </div>
                    <hr>
                    <form action="{{ url('pelanggan/store', ['id' => $pelanggan->id]) }}" class="row g-3"
                        enctype="multipart/form-data" method="POST">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label" for="oncard_user_id">ONCARD USER ID</label>
                            <input class="form-control" id="oncard_user_id" name="oncard_user_id"
                                placeholder="user id pada oncard" required type="number"
                                value="{{ old('oncard_user_id', $pelanggan->oncard_user_id) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="oncard_account_number">NOMOR ACCOUNT ONCARD</label>
                            <input class="form-control" id="oncard_account_number" name="oncard_account_number"
                                placeholder="nomor akun oncard, 'rekening users'" required type="text"
                                value="{{ old('oncard_account_number', $pelanggan->oncard_account_number) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="nama">NAMA PELANGGAN</label>
                            <input class="form-control" id="nama" name="nama" placeholder="Nama Pelanggan" required
                                type="text" value="{{ old('nama', $pelanggan->nama) }}" />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="user_type">USER TYPE</label>
                            <select class="form-select" id="user_type" name="user_type" required>
                                <option value="">Pilih User Type</option>
                                <option {{ old('user_type', $pelanggan->user_type) == 'siswa' ? 'selected' : '' }}
                                    value="siswa">siswa</option>
                                <option {{ old('user_type', $pelanggan->user_type) == 'general' ? 'selected' : '' }}
                                    value="general">general</option>
                                <option {{ old('user_type', $pelanggan->user_type) == 'merchant' ? 'selected' : '' }}
                                    value="merchant">merchant</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="detail">keterangan</label>
                            <textarea class="form-control" id="detail" name="detail" placeholder="Keterangan">{{ old('detail', $pelanggan->detail) }}</textarea>
                        </div>
                        {{-- user --}}
                        <div class="col-12 mb-3">
                            <div class="border-top border-0 border-4 border-primary pt-2">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label" for="username">USERNAME</label>
                                        <input class="form-control" id="username" name="username" placeholder="username"
                                            required type="text"
                                            value="{{ old('username', $pelanggan->user->username) }}" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="password">PASSWORD</label>
                                        <input autocomplete="new-password" class="form-control" id="password"
                                            name="password" placeholder="Password" type="password"
                                            value="{{ old('password') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 border-top"></div>
                        <div class="col-12 flex justify-end">
                            <button class="btn btn-outline-success" type="submit">
                                <i class='bx bx-save me-0'></i> Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
