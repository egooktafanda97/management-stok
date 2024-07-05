@extends('Template.layout')
@push('style')
    <script src="https://cdn.tailwindcss.com"></script>
    <link crossorigin="anonymous" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endpush
@section('content')
    <script defer src="https://unpkg.com/alpinejs-money@latest/dist/money.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.2/axios.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentDebs', () => ({
                userData: {},
                debs_id: '',
                user_debs_id: '',
                metode: '',
                nominal: '',
                keterangan: '',
                nama: '',
                bayar(data) {
                    this.userData = JSON.parse(data ?? '[]');
                    this.user_debs_id = this.userData.id;
                    this.debs_id = this.userData.id;
                    this.nama =
                        `${this.userData?.general_actor?.nama??''} (${this.userData?.general_actor?.oncard_account_number ?? ''})`;
                },
                bayarSemua() {
                    this.nominal = 'Rp. ' + fRp(`${this.userData.total}`);
                },
                payments() {
                    if (this.metode == '') {
                        swal('Error', 'Pilih Metode Pembayaran', 'error');
                        return;
                    }
                    if (this.nominal == '') {
                        swal('Error', 'Nominal tidak boleh kosong', 'error');
                        return;
                    }

                    if (this.keterangan == '') {
                        swal('Error', 'Keterangan tidak boleh kosong', 'error');
                        return;
                    }
                    let nominal = deconvertRp(this.nominal);

                    if (nominal > this.userData.total) {
                        swal('Error', 'Nominal tidak boleh melebihi total hutang', 'error');
                        return;
                    }

                    let data = {
                        id: this.debs_id,
                        user_debs_id: this.user_debs_id,
                        metode: this.metode,
                        nominal: nominal,
                        keterangan: this.keterangan
                    }

                    axios.post('/api/payment/pay-debs', data, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer {{ !empty(auth()->user()) ? auth()->guard('api')->login(auth()->user()) : null }}`
                        }
                    }).then((res) => {
                        swal('Success', 'Pembayaran berhasil', 'success');
                        this.userData = {};
                        this.user_debs_id = '';
                        this.metode = '';
                        this.nominal = '';
                        this.keterangan = '';
                    }).catch((err) => {
                        swal('Error', 'Pembayaran gagal', 'error');
                    })
                }
            }))
        })
    </script>
    <div class="page-wrapper" x-data="paymentDebs">
        <div class="page-content">
            <!--breadcrumb-->
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="ps-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 p-0">
                            <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                            </li>
                            <li aria-current="page" class="breadcrumb-item active">Pembayaran Hutang</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!--end breadcrumb-->
            <hr />
            <div class="row mt-2">
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="example" style="width:100%">
                                    <thead>
                                        <tr>
                                            <td>No</td>
                                            <td>Nama Pelanggan</td>
                                            <td>Total Hutang</td>
                                            <td>#</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $key => $item)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $item->generalActor->nama }}</td>
                                                <td>{{ App\Utils\Helpers::rupiah($item->total) }}</td>
                                                <td>
                                                    <button class="btn btn-primary"
                                                        x-on:click='bayar(`@json($item)`)'>Bayar</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{-- <pre x-text="JSON.stringify(userData, null, 2)"></pre> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Pembayaran Hutang</h5>
                            <div class="form-group row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Nama Pelanggan</label>
                                    <input :value="nama ?? ''" class="form-control" readonly type="text">
                                    <input type="hidden" x-model="user_debs_id">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 mb-3">
                                    <label for="">Metode Pembayaran</label>
                                    <select class="form-control" id="metode" name="metode" x-model="metode">
                                        <option value="">Pilih Metode Pembayaran</option>
                                        @foreach ($metode as $item)
                                            @if ($item->name != 'DEBS')
                                                <option value="{{ $item->id }}">{{ $item->alias }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Nominal</label>
                                    <div class="flex w-full justify-between items-center">
                                        <div class="w-full">
                                            <input class="form-control w-full" id="rupiah" name="nominal" type="text"
                                                x-model="nominal">
                                        </div>
                                        <div>
                                            <button @click="bayarSemua" class="btn w-[150px] ml-2 btn-primary">Bayar
                                                Semua</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- <div class="form-group row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Sisa Hutag</label>
                                    <input :value="fRp(userData?.total ?? 0, 'Rp. ')" class="form-control" id="rupiah"
                                        name="sisa" type="text">
                                </div>
                            </div> --}}

                            <div class="form-group row">
                                <div class="col-md-12 mb-3">
                                    <label for="">Keterangan</label>
                                    <textarea class="form-control" cols="2" id="keterangan" name="keterangan" rows="2" x-model="keterangan"></textarea>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12 mb-3 w-full flex justify-end">
                                    <button @click="payments" class="btn btn-primary w-[30%]" id="btnBayar">Bayar</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $('#rupiah').on('keyup', function(e) {
            // tambahkan 'Rp.' pada saat form di ketik
            // gunakan fungsi formatRupiah() untuk mengubah angka yang di ketik menjadi format angka
            $(this).val(fRp($(this).val(), 'Rp. '));
        });

        /* Fungsi formatRupiah */
        function fRp(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, "").toString(),
                split = number_string.split(","),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? "." : "";
                rupiah += separator + ribuan.join(".");
            }

            rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
            return prefix == undefined ? rupiah : rupiah ? "Rp. " + rupiah : "";
        }

        function deconvertRp(rupiah) {
            return parseInt(rupiah.replace(/[^,\d]/g, ""));
        }
    </script>
@endpush
