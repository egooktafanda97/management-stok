@extends('Template.layout')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body">
                    <ul class="nav nav-tabs nav-primary" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a aria-selected="true" href="{{ url('produk') }}">
                                <div class="flex justify-center h-full align-items-center w-[30px]">
                                    {{-- back --}}
                                    <div class="tab-icon text-lg"><i class="bx bx-arrow-to-left"></i></div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a aria-selected="true" class="nav-link active" data-bs-toggle="tab" href="#unit_priece"
                                role="tab">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon"><i class="bx bx-home font-18 me-1"></i></div>
                                    <div class="tab-title">Unit Harga</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a aria-selected="false" class="nav-link" data-bs-toggle="tab" href="#konversi" role="tab"
                                tabindex="-1">
                                <div class="d-flex align-items-center">
                                    <div class="tab-icon">
                                        <i class="bx bx-user-pin font-18 me-1"></i>
                                    </div>
                                    <div class="tab-title">Satuan Konversi</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content py-3">
                        <div class="tab-pane fade show active" id="unit_priece" role="tabpanel">
                            {{-- unit priece --}}
                            <div class="flex justify-between mb-2">
                                <div>
                                    <h6 class="mb-0 text-uppercase">DATA UNIT KONVERSI</h6>
                                </div>
                                <div>
                                    <button class="btn btn-success" data-bs-target="#modal-unit-priece"
                                        data-bs-toggle="modal" type="button">
                                        <i class='bx bx-plus-circle'></i>
                                        TAMBAH DATA
                                    </button>
                                </div>
                            </div>
                            <hr />
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="example" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>NAMA</th>
                                                    <th>SATUAN JUAL</th>
                                                    <th>HARGA</th>
                                                    <th>Diskon</td>
                                                    <th>Harga Total</td>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($unitConvert as $key => $units)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $units->name }}</td>
                                                        <td>{{ $units->jenisSatuanJual->name ?? '' }}</td>
                                                        <td>{{ 'Rp. ' . number_format($units->priece) }}</td>
                                                        <td>{{ $units->diskon }}%
                                                            {{ 'Rp. ' . number_format(($units->priece * $units->diskon) / 100) }}
                                                        </td>
                                                        <td>{{ 'Rp. ' . number_format($units->priece - ($units->priece * $units->diskon) / 100) }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary btn-edit-unit-priece"
                                                                data-bs-target="#modal-unit-priece" data-bs-toggle="modal"
                                                                data-json="{{ json_encode($units) }}" type="button">
                                                                <i class='bx bx-pencil'></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger btn-delete destory-items"
                                                                data-id="{{ $units->id }}"
                                                                data-url="{{ url('produk/addons/delete/unit') }}/{{ $units->id }}"
                                                                type="button">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </td>
                                                        </>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        {{-- paginate --}}
                                        <div
                                            class="text-end
                                                                w-100 align-end">
                                            {{-- {!! $unitConvert->links('pagination::bootstrap-4') !!} --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="konversi" role="tabpanel">
                            {{-- konversi --}}
                            {{-- unit priece --}}
                            <div class="flex justify-between mb-2">
                                <div>
                                    <h6 class="mb-0 text-uppercase">SATUAN KONVERSI</h6>
                                </div>
                                <div>
                                    <button class="btn btn-success" data-bs-target="#model-unit-conversion"
                                        data-bs-toggle="modal" type="button">
                                        <i class='bx bx-plus-circle'></i>
                                        TAMBAH DATA
                                    </button>
                                </div>
                            </div>
                            <hr />
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered" id="example" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>KONVERSI SATUAN DARI</th>
                                                    <th>KONVERSI SATUAN KE</th>
                                                    <th>NILAI KONVERSI</th>
                                                    <th>#</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($conversion as $key => $cnv)
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $cnv->satuanKonversi->name ?? '' }}</td>
                                                        <td>{{ $cnv->satuan->name ?? '' }}</td>
                                                        <td>{{ '1 ' . $cnv->satuan->name . ' = ' . $cnv->nilai_konversi . ' ' . $cnv->satuanKonversi->name }}
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-sm btn-primary btn-edit-conversi"
                                                                data-bs-target="#model-unit-conversion"
                                                                data-bs-toggle="modal" data-json="{{ json_encode($cnv) }}"
                                                                type="button">
                                                                <i class='bx bx-pencil'></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-danger btn-delete destory-items"
                                                                data-id="{{ $cnv->id }}"
                                                                data-url="{{ url('produk/addons/delete/konversi') }}/{{ $cnv->id }}"
                                                                type="button">
                                                                <i class='bx bx-trash'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                        {{-- paginate --}}
                                        <div class="text-end w-100 align-end">
                                            {{-- {!! $unitConvert->links('pagination::bootstrap-4') !!} --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- modal add unit priece --}}
    <div aria-hidden="true" aria-labelledby="modal-unit-priece" class="modal fade" id="modal-unit-priece" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-unit-priece">Tambahkan Satuan Jual</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>

                <div class="modal-body p-3">
                    <form action="" class="row g-3" enctype="multipart/form-data" id="form-unit-priece"
                        method="POST">
                        @csrf
                        <input name="produks_id" type="hidden" value="{{ $id }}">
                        <!-- Token CSRF -->
                        <div class="col-12 mb-2">
                            <label class="form-label" for="barcode">Name</label>
                            <input class="form-control" id="name" name="name"
                                placeholder="Nama Unit Jual ... box" type="text" />
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="satuan_id">SATUAN JUAL</label>
                            <select class="form-control" id="satuan_id_unit" name="satuan_id">
                                <option value="">-- Pilih Satuan Jual --</option>
                                @foreach ($satuanByConversi as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 mb-2">
                            <label class="form-label" for="harga">Harga Jual</label>
                            <input class="form-control" id="harga" name="harga" placeholder="Harga Jual"
                                type="text" />
                        </div>

                        <div class="col-12 mb-2">
                            <label class="form-label" for="diskon">Diskon</label>
                            <div class="flex justify-between items-center">
                                <input class="form-control" id="diskon" name="diskon" placeholder="diskon %"
                                    type="text" />
                                <input class="form-control w-[150px]" id="total_diskon" name="total_diskon"
                                    placeholder="Total Diskon" readonly type="text" />
                            </div>
                        </div>

                        <div class="w-100 border-top mb-2"></div>
                        <div class="col" style="display: flex; justify-content: flex-end; align-items: center">
                            <div>
                                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Tutup</button>
                                <button class="btn btn-outline-success" type="submit">
                                    <i class='bx bx-save me-0'></i>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal satuan konversi --}}
    <div aria-hidden="true" aria-labelledby="model-unit-conversion" class="modal fade" id="model-unit-conversion"
        tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="model-unit-conversion">Konversi Satuan</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body p-3">
                    <form action="{{ url('produk/addons/konversi') }}" class="row g-3" enctype="multipart/form-data"
                        id="form-konversi" method="POST">
                        @csrf

                        <input name="produks_id" type="hidden" value="{{ $id }}">
                        <!-- Token CSRF -->

                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="satuan_konversi_id">SATUAN KONVERSI DARI</label>
                            <select class="form-control" id="satuan_konversi_id" name="satuan_konversi_id">
                                <option value="">-- Pilih Satuan Konversi --</option>
                                @foreach ($satuan as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label" for="satuan_id">SATUAN KONVERSI KE</label>
                            <select class="form-control" id="satuan_id" name="satuan_id">
                                <option value="">-- Pilih Satuan Jual --</option>
                                @foreach ($satuan as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                                @endforeach
                            </select>
                        </div>



                        <div class="col-12 mb-2">
                            <label class="form-label" for="nilai_konversi">Nilai Konversi</label>
                            <input class="form-control" id="nilai_konversi" name="nilai_konversi"
                                placeholder="nilai konversi" type="text" />
                        </div>
                        <div class="w-100 border-top mb-2"></div>
                        <div class="col" style="display: flex; justify-content: flex-end; align-items: center">
                            <div>
                                <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Tutup</button>
                                <button class="btn btn-outline-success" type="submit">
                                    <i class='bx bx-save me-0'></i>
                                    Simpan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
    <script src="https://cdn.tailwindcss.com"></script>
@endpush

@push('script')
    <script>
        // edit item unit priece
        $(".btn-edit-unit-priece").click(function() {
            let data = $(this).data('json');
            console.log(data, data.jenis_satuan_jual_id)
            $('#name').val(data.name);
            $('#satuan_id_unit')
                .append(
                    `<option value="${data.jenis_satuan_jual_id}" selected>${data.jenis_satuan_jual.name}</option>`)
            $('#harga').val(data.priece);
            $('#form-unit-priece').attr('action', `{{ url('produk/addons') }}/${data.id}`);
        })
        // edit item konversi
        $(".btn-edit-conversi").click(function() {
            let data = $(this).data('json');
            $('#satuan_konversi_id').val(data.satuan_konversi_id);
            $('#satuan_id').val(data.satuan_id);
            $('#nilai_konversi').val(data.nilai_konversi);
            $('#form-konversi').attr('action', `{{ url('produk/addons/konversi') }}/${data.id}`);
        })
        $("#harga, #diskon").on('keyup', function() {
            let harga = $("#harga").val();
            let diskon = $("#diskon").val(); // presentase
            let total_diskon = harga * diskon / 100;
            $("#total_diskon").val(`${rupiah(total_diskon)}`);
        })
    </script>
@endpush
