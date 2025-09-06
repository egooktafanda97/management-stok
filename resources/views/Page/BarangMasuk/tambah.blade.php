@extends('Template.layout')
@push('style')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.2/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
@endpush
@section('content')
    <div class="page-wrapper" x-data="{
        id: null,
        barang_masuk: {},
        formData: {
            produk_id: null,
            supplier_id: null,
            harga_beli: null,
            satuan_beli_id: null,
            jumlah_barang_masuk: null
        },
        SatuanBelis: [],
        barangMasukHandler: async function() {
            axios.get('/api/barangmasuk/today', {
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer {{ Auth::guard('api')->login(auth()->user()) }}'
                }
            }).then((response) => {
                // console.log(response.data);
                this.barang_masuk = response.data;
            });
        },
        editBarangMasuk: function(data) {
            this.id = data.id;
            // this.formData.produk_id = data.produk_id;
            $('.js-data-example-ajax').select2('trigger', 'select', {
                data: {
                    id: data.produks_id,
                    name: data.produk.name
                }
            });
            this.formData.supplier_id = data?.supplier_id ?? '';
            this.formData.harga_beli = data.harga_beli;
            this.formData.satuan_beli_id = data.satuan_beli_id;
            this.formData.jumlah_barang_masuk = data.jumlah_barang_masuk;
            ((data) => {
                axios.get(`/api/barangmasuk/produk/${data.produks_id}/satuan`, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': 'Bearer {{ Auth::guard('api')->login(auth()->user()) }}'
                        }
                    })
                    .then((response) => {
                        // satuanBeli = response.data;
                        SatuanBelis = response.data;
                        // set selected name satuan_beli_id
                        document.getElementById('satuan_beli_id').value = data.satuan_beli_id;
                    });
            })(data)
        }
    }" x-init="barangMasukHandler()">
        <div class="page-content">
            <div class="row">
                <div class="col-12 mb-2">
                    <div
                        class="border-top border-0 border-4 rounded bg-white shadow-sm pt-2 pb-2 flex justify-between items-center h-full">
                        <div class="flex">
                            <a aria-selected="true" href="{{ url('barangmasuk') }}">
                                <div class="flex justify-center h-full align-items-center w-[30px]">
                                    {{-- back --}}
                                    <div class="tab-icon text-lg"><i class="bx bx-arrow-to-left"></i></div>
                                </div>
                            </a>
                            <h4 class="card-title text-lg">Barang Masuk</h4>
                        </div>
                        {{-- <div>
                        <a class="btn btn-secondary btn-sm mr-2">
                            <i class="bx bx-printer"></i> Cetak
                        </a>
                    </div> --}}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body">
                            <div x-data="{
                                produk_selected: null,
                                satuanBeli: [],
                            }">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="produks_id">Produk</label>
                                            <select class="form-control js-data-example-ajax" x-init="(() => {
                                                $('.js-data-example-ajax').on('select2:select', function(e) {
                                                    var data = e.params.data;
                                                    this.produk_selected = data.id;
                                                    formData.produk_id = data.id;
                                                    axios.get(`/api/barangmasuk/produk/${data.id}/satuan`, {
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'Authorization': `Bearer {{ !empty(auth()->user())? auth()->guard('api')->login(auth()->user()): null }}`,
                                                            }
                                                        })
                                                        .then((response) => {
                                                            // satuanBeli = response.data;
                                                            SatuanBelis = response.data;
                                                        });
                                                });
                                            })"
                                                x-model="produk_selected"></select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label" for="supplier_id">Supplier</label>
                                        <select class="form-control" name="supplier_id" x-model="formData.supplier_id">
                                            <option value="">-- Pilih Supplier --</option>
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="harga_beli">Harga Beli / Satuan Beli</label>
                                        <input class="form-control" id="harga_beli" name="harga_beli"
                                            placeholder="Harga Beli" required type="number" x-model="formData.harga_beli">
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="satuan_beli_id">Satuan Beli</label>
                                        <select class="form-control" id="satuan_beli_id" name="satuan_beli_id" required
                                            x-model="formData.satuan_beli_id">
                                            <option value="">-- Pilih Satuan Beli --</option>
                                            <template :key="satuan.id" x-for="satuan in SatuanBelis">
                                                <option :value="satuan.id" x-text="satuan.name"></option>
                                            </template>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label" for="jumlah_barang_masuk">Jumlah Barang Masuk</label>
                                        <input class="form-control" id="jumlah_barang_masuk" name="jumlah_barang_masuk"
                                            placeholder="Jumlah Barang Masuk" required type="number"
                                            x-model="formData.jumlah_barang_masuk">
                                    </div>

                                    <div class="col-12" x-data="{
                                        async save() {
                                            await axios.post(`/api/barangmasuk${id?'/'+id+'/save':''}`, formData, {
                                                    headers: {
                                                        'Content-Type': 'application/json',
                                                        'Authorization': 'Bearer {{ Auth::guard('api')->login(auth()->user()) }}'
                                                    }
                                                })
                                                .then((response) => {
                                                    swal({
                                                        title: 'Success',
                                                        text: response.data.message,
                                                        icon: 'success',
                                                        button: 'OK'
                                                    });
                                                    barangMasukHandler()
                                                    satuanBeli = [];
                                                    document.getElementById('harga_beli').value = '';
                                                    document.getElementById('jumlah_barang_masuk').value = '';
                                                    document.getElementById('supplier_id').value = '';
                                                    document.getElementById('satuan_beli_id').value = '';
                                                    document.getElementById('produks_id').value = '';
                                                    document.getElementById('produks_id').value = '';
                                                    // $('.js-data-example-ajax').val(null).trigger('change');
                                                }).catch((error) => {
                                                    swal({
                                                        title: 'Error',
                                                        text: error.response.data.message,
                                                        icon: 'error',
                                                        button: 'OK'
                                                    });
                                                });
                                        }
                                    }">
                                        <hr class="mb-2">
                                        <div class="w-full flex justify-end items-center">
                                            <button @click="save" class="btn btn-outline-success">
                                                <i class='bx bx-save'></i> Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card border-top border-0 border-4 border-success">
                        <div class="card-body">
                            <div class="pb-3">
                                <strong>{{ \Carbon\Carbon::now()->format('d M Y H:s:i') }}</strong>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tanggal</th>
                                            <th>Produk</th>
                                            <th>Supplier</th>
                                            <th>Harga Beli / Satuan</th>
                                            <th>Stok Sebelumnya</th>
                                            <th>Jumlah Masuk</th>
                                            <th>Stok Tersedia</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template :key="item.id" x-for="(item, index) in barang_masuk?.data ?? []">
                                            <tr>
                                                <td x-text="index + 1"></td>
                                                <td x-text="moment(item.tanggal).format('Y-m-d H:s:i')"></td>
                                                <td x-text="item.produk.name"></td>
                                                <td x-text="item?.supplier?.name ?? ''"></td>
                                                <td x-text="item.harga_beli"></td>
                                                <td x-text="item.jumlah_sebelumnya"></td>
                                                <td
                                                    x-text="`${item.jumlah_barang_masuk} / ${item?.satuan_beli?.name ?? ''}`">
                                                </td>
                                                <td
                                                    x-text="`${(item.jumlah_sebelumnya + item.jumlah_barang_masuk)} ${item?.satuan_sebelumnya?.name ?? ''}`">
                                                </td>
                                                <td>
                                                    {{-- edit --}}
                                                    <button @click="editBarangMasuk(item)"
                                                        class="btn btn-warning btn-sm destory-items"
                                                        title="edit barang masuk" x-show="item.produkPalingBaru ?? false">
                                                        <i class='bx bx-edit '></i>
                                                    </button>
                                                    <button @click="destorys(item.id, `/barangmasuk/cencel/${item.id}`)"
                                                        class="btn btn-danger btn-sm destory-items"
                                                        title="cancel barang masuk" x-show="item.produkPalingBaru ?? false">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('.js-data-example-ajax').select2({
            ajax: {
                url: '/api/produk/search',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer {{ !empty(auth()->user())? auth()->guard('api')->login(auth()->user()): null }}`,
                },
                data: function(params) {
                    var query = {
                        search: params.term,
                        type: 'public'
                    }
                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function(data) {
                    return {
                        results: data
                    };
                },
            },
            placeholder: 'Search for a repository',
            minimumInputLength: 1,
            templateResult: formatResult,
            templateSelection: formatSelection
        });

        function formatResult(item) {
            if (item.loading) {
                return item.text;
            }
            var $container = $('<div></div>');
            $container.text(item.name);
            return $container;
        }

        function formatSelection(item) {
            return item.name || item.text; // sesuaikan dengan data yang Anda tampilkan
        }

        function destorys(id, url) {
            console.log(id, url);
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        window.location.href = url
                    }
                });
        }
    </script>
@endpush
