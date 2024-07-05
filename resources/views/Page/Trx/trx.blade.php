@extends('Template.layout')
@push('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script defer src="https://unpkg.com/alpinejs-money@latest/dist/money.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
    function openPrintWindow($inv) {
        const printWindow = window.open(`{{ url('trx/faktur') }}/${$inv}`, 'PrintWindow', 'width=800,height=600');
        printWindow.focus();
    }
    $(document).ready(function() {
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
    });

    function deconvertRp(rupiah) {
        return parseInt(rupiah.replace(/[^,\d]/g, ""));
    }
</script>
@endpush


@push('style')
<script src="https://cdn.tailwindcss.com"></script>
<link crossorigin="anonymous" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.7.2/axios.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .loading-spinner {
        border: 2px solid #f3f3f3;
        /* Light grey */
        border-top: 2px solid #3498db;
        /* Blue */
        border-radius: 50%;
        width: 16px;
        height: 16px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .select2-container {
        width:100%!important;
    }
    .card {
        box-shadow: 0 2px 2px rgb(218 218 253 / 35%), 0 2px 6px 0 rgb(206 206 238 / 3%)!important;
    }
</style>
@endpush
@section('content')
<script>
    document.addEventListener('alpine:init', () => {
        const interfaceUnitPrice = (unitPrice) => {
            return {
                id: unitPrice.id,
                produks_id: unitPrice.produks_id,
                unit: unitPrice.unit,
                unit_name: unitPrice.unit_name,
                price: unitPrice.price,
                discount: unitPrice.discount,
                IdrDiscount: unitPrice.IdrDiscount,
                total: unitPrice.price - unitPrice.IdrDiscount
            }
        };
        const interfaceProdukOrder = (produkOrder) => {
            return {
                id: produkOrder.id,
                name: produkOrder.name,
                price_actual: produkOrder.price_actual,
                discount: produkOrder.discount,
                IdrDiscount: produkOrder.IdrDiscount,
                price: produkOrder.price,
                qty: 1,
                satuan_active: produkOrder.satuan_active,
                subTotal: produkOrder.price,
                unitPriceList: produkOrder?.unitPriceList ?? []
            }
        };
        Alpine.data('trx', () => ({
            setUpData: [], // setup data
            ListProduk: [], // list produk yang diambil dari api
            produkOrder: [], // list produk yang diorder
            produkSerachModel: [], // list model produk yang dicari
            ringkasanTotal: 0,
            diskon: 0,
            pphStatus: true,
            pph: 0,
            diskonIdr: 0,
            pphIdr: 0,
            subTotalBuyer: 0,
            listPayType: [],
            payTypeActived: 3,
            uangPelanggan: '',
            kembalian: 0,
            listUser: [],
            userSelected: {},
            invoice: '',
            pelangganSearchCard: false,
            pinOncardPayment: "",
            resetData() {
                this.setUpData = [];
                this.ListProduk = [];
                this.produkOrder = [];
                this.produkSerachModel = [];
                this.ringkasanTotal = 0;
                this.diskon = 0;
                this.diskonIdr = 0;
                this.subTotalBuyer = 0;
                this.invoice = '';
                this.initializeSetupTrx();
                $('.user-search').val(null).trigger('change');
                $("#rupiah").val(null);
                this.uangPelanggan = '';
                this.kembalian = 0;
            },
            initializeSetupTrx() {
                fetch('/api/trx/init-trx', {
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer {{ !empty(auth()->user()) ? auth()->guard('api')->login(auth()->user()) : null }}`,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        init = data;
                        try {
                            init_pph = init.config.find(item => item.key == 'pph').value;
                            if (init_pph == 0) {
                                this.pphStatus = false
                            }
                        } catch (error) {
                            init_pph = 0;
                            this.pphStatus = false
                        }
                        this.pph = init_pph;
                        this.pphIdr = 0;
                        listPaymentType = init.payment_types;
                        this.listPayType = listPaymentType;
                        this.payTypeActived = listPaymentType.find(item => (item.name ==
                            'ONCARD' ||
                            item
                            .name == 'oncard')).id;
                        this.setUpData = init;
                    })
                    .catch(error => console.error(error));
            },
            init() {
                const self = this;
                this.initializeSetupTrx();
                //order produk api search

                $('#produk-search').select2({
                    ajax: {
                        url: '/api/produk/search',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer {{ !empty(auth()->user()) ? auth()->guard('api')->login(auth()->user()) : null }}`,
                        },
                        data: function(params) {
                            var query = {
                                search: params.term,
                                type: 'public'
                            }
                            return query;
                        },
                        processResults: function(data) {
                            console.log(data);
                            this.ListProduk = data;
                            return {
                                results: data
                            };
                        },
                    },
                    placeholder: 'Cari barang dengan scan barcode / qr-code / nama...',
                    minimumInputLength: 1,
                    templateResult: this.formatResult,
                    templateSelection: this.formatSelection
                });
                // order produk search by selected
                $('#produk-search').on('select2:select', function(e) {
                    var data = e.params.data;
                    self.addToProdOrder(data);
                    $(this).val(null).trigger('change');
                });
                /**@argument
                 * search user
                 */
                $('.user-search').select2({
                    ajax: {
                        url: '/api/pelanggan/search/nopaginate',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer {{ !empty(auth()->user()) ? auth()->guard('api')->login(auth()->user()) : null }}`,
                        },
                        data: function(params) {
                            var query = {
                                q: params.term,
                                card: self.pelangganSearchCard
                            }
                            return query;
                        },
                        processResults: function(data) {
                            this.listUser = data;
                            return {
                                results: data
                            };
                        },
                    },
                    placeholder: 'Search user by name or card number',
                    minimumInputLength: 1,
                    templateResult: this.formatResultUser,
                    templateSelection: this.formatSelectionUser
                });

                // user search by selected
                $('.user-search').on('select2:select', function(e) {
                    var data = e.params.data;
                    self.userSelected = data;
                });
            },
            unitPricesConvert(init_unit, qty = 1) {
                const price = init_unit?.priece ?? init_unit?.price ?? 0;
                const diskon = init_unit?.diskon ?? init_unit?.discount ?? 0;
                return {
                    price_actual: price,
                    discount: diskon,
                    IdrDiscount: price * (diskon / 100),
                    price: price -
                        (price * (diskon / 100)),

                    subTotal: (price -
                        (price * (diskon / 100))) * qty,
                }
            },
            addToProdOrder(prod) {
                this.produkSerachModel.push(prod);
                if (this.produkOrder.find(item => item.id == prod.id) == undefined) {
                    prod.qty = 1;
                    const ProdukOrderCollect = interfaceProdukOrder({
                        id: prod.id,
                        name: prod.name,
                        qty: 1,
                        ...(this.unitPricesConvert(prod.init_unit) ?? {}),
                        satuan_active: prod.init_unit,
                        unitPriceList: prod.unit_prieces.map(unitPrice =>
                            interfaceUnitPrice(interfaceUnitPrice({
                                id: unitPrice.id,
                                produks_id: prod.id,
                                unit: unitPrice?.jenis_satuan_jual_id ?? null,
                                unit_name: unitPrice?.jenis_satuan_jual?.name ??
                                    null,
                                price: unitPrice.priece,
                                discount: unitPrice.diskon,
                                IdrDiscount: unitPrice.priece * (prod
                                    ?.init_unit?.diskon / 100),
                            })))
                    });
                    this.produkOrder.push(ProdukOrderCollect);
                    this.summaryTotal();
                }

            },
            incrementQty(id) {
                const index = this.produkOrder.findIndex(item => item.id == id);
                this.produkOrder[index].qty += 1;
                this.produkOrder[index].subTotal = this.produkOrder[index].price * this.produkOrder[
                    index].qty;
                this.produkOrder = [...this.produkOrder];
                this.summaryTotal();
            },
            decrementQty(id) {
                const index = this.produkOrder.findIndex(item => item.id == id);
                if (this.produkOrder[index].qty > 1) {
                    this.produkOrder[index].qty -= 1;
                    this.produkOrder[index].subTotal = this.produkOrder[index].price * this
                        .produkOrder[index].qty;
                    this.produkOrder = [...this.produkOrder];
                    this.summaryTotal();
                }
            },
            summaryTotal() {
                this.pembayaranTunai(this.uangPelanggan);
                this.ringkasanTotal = this.produkOrder.reduce((acc, item) => acc + item.subTotal,
                    0);
                const totalDiskonIdr = this.diskonBuyerHandler();
                this.subTotalBuyer = this.ringkasanTotal - totalDiskonIdr;
                // pph 
                var totals = this.subTotalBuyer;
                if (this.pphStatus) {
                    this.pphIdr = this.subTotalBuyer * (this.pph / 100);
                    totals = this.subTotalBuyer + this.pphIdr;
                }
                this.subTotalBuyer = totals;
            },
            formatRupiah(value) {
                if (typeof value !== 'number') {
                    value = parseFloat(value) || 0;
                }
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            },
            formatResult(item) {
                if (item.loading) {
                    return item.text;
                }
                var $container = $('<div></div>');
                $container.text(item.name);
                return $container;
            },
            formatSelection(item) {
                return item.name || item.text; // sesuaikan dengan data yang Anda tampilkan
            },

            formatResultUser(item) {
                if (item.loading) {
                    return item.text;
                }
                var $container = $('<div></div>');
                $container.text(item.nama);
                return $container;
            },
            formatSelectionUser(item) {
                return item.nama || item
                    .oncard_account_number; // sesuaikan dengan data yang Anda tampilkan
            },

            handleSelectProduk() {
                console.log(this.SelectedProdukId);
            },
            hndelChangeUnit(id, unitId) {
                const prodIndex = this.produkOrder.findIndex(item => item.id == id);
                const prod = this.produkOrder[prodIndex];
                const unitPrice = this.produkOrder[prodIndex].unitPriceList.find(unitPrice =>
                    unitPrice.id == unitId);
                const updatedUnitPrices = this.unitPricesConvert(unitPrice, prod.qty);
                this.produkOrder[prodIndex] = {
                    ...prod,
                    ...updatedUnitPrices,
                    satuan_active: unitPrice
                };
                this.summaryTotal();
            },
            removeFromProdOrder(id) {
                const index = this.produkOrder.findIndex(item => item.id == id);
                this.produkOrder.splice(index, 1);
                this.produkOrder = [...this.produkOrder];
                const indexSearchModel = this.produkSerachModel.findIndex(item => item.id == id);
                this.produkSerachModel.splice(indexSearchModel, 1);
                this.summaryTotal();
            },
            discountChangeKeyup(e) {
                this.diskon = e.target.value;
                this.diskonBuyerHandler();
                this.summaryTotal();
            },
            diskonBuyerHandler() {
                const diskonIdr = this.ringkasanTotal * (this.diskon / 100);
                this.diskonIdr = diskonIdr;
                return diskonIdr;
            },
            unCheckPph(e) {
                if (!e) {
                    this.pphIdr = 0;
                    this.pphStatus = false;
                    this.summaryTotal();
                } else {
                    this.pphStatus = true;
                    this.pph = this.setUpData.config.find(item => item.key == 'pph').value;
                    this.summaryTotal();
                }
            },
            pembayaranTunai(value) {
                this.uangPelanggan = value;
                this.kembalian = value - this.subTotalBuyer;
                if (isNaN(this.kembalian)) {
                    this.kembalian = `Rp.0`;
                }
            },
            pelangganSearch() {
                fetch(`/api/pelanggan/search?q=${this.searchQueryUser}`, {
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer {{ !empty(auth()->user()) ? auth()->guard('api')->login(auth()->user()) : null }}`,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.loading = false;
                        this.listUser = data.data;
                    })
                    .catch(error => {
                        this.prodSerach = []
                        this.loading = false;
                    })
            },
            produkOrderFormatingPush() {
                return this.produkOrder.map(item => {
                    return {
                        id: item.id,
                        qty: item.qty,
                        harga: item.price,
                        satuan: item.satuan_active.jenis_satuan_jual_id ?? item
                            .satuan_active.unit ?? null,
                    }
                });
            },
            collectDataPush() {
                return {
                    orders: this.produkOrderFormatingPush(),
                    diskon: this.diskon,
                    pph: this.pph,
                    total: this.subTotalBuyer,
                    payType: this.payTypeActived,
                    user: this.userSelected.id,
                    total_pembayaran: this.uangPelanggan,
                    pin: this.pinOncardPayment,
                    token: "test"
                }
            },
            hndelPayTypeChange(e) {
                this.payTypeActived = e.target.value;

                if (e.target.value == 3) {
                    this.pelangganSearchCard = true;
                }

                if (e.target.value != 1) {
                    this.uangPelanggan = '';
                    this.kembalian = 0;
                }
            },
            async trxSubmit() {
                // console.log(this.collectDataPush());

                $('#btnSubmit').html(`<div class="spinner-border text-white" style="width:1em!important; height:1em!important;" role="status">
                <span class="sr-only">Loading...</span>
                </div> Checkout`);
                $('#btnSubmit').prop('disabled', true);

                const data = this.collectDataPush();
                const response = await axios.post('/api/trx/shop', data, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer {{ !empty(auth()->user()) ? auth()->guard('api')->login(auth()->user()) : null }}`
                    }
                }).catch(error => {
                    swal({
                        title: 'Error!',
                        text: error.response?.data?.msg,
                        icon: 'error',
                        button: 'OK'
                    }).then(() => {
                        this.exeevn = 0;
                    });
                    $('#btnSubmit').html(`Checkout`);
                    $('#btnSubmit').prop('disabled', false);
                });

                if (response?.status === 200) {
                    this.invoice = response.data?.data?.invoiceDTOs?.invoice_id ?? '';
                    swal({
                        title: 'Success!',
                        text: 'Faktur telah berhasil dibuat.',
                        icon: 'success',
                        buttons: {
                            clear: {
                                text: 'Clear',
                                value: 'clear'
                            },
                            print: {
                                text: 'Print Faktur',
                                value: 'print'
                            }
                        }
                    }).then(value => {
                        switch (value) {
                            case 'clear':
                                this.resetData();
                                location.reload();
                                break;
                            case 'print':
                                openPrintWindow(this.invoice);
                                this.resetData();
                                location.reload();
                                break;
                            default:
                                break;
                        }
                    });

                    $('#btnSubmit').html(`Checkout`);
                    $('#btnSubmit').prop('disabled', false);

                }else {
                    $('#btnSubmit').html(`Checkout`);
                    $('#btnSubmit').prop('disabled', false);
                }
            }
        }));
    });
</script>
<div class="page-wrapper" x-data="trx">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="card" style="border-radius:5px!Important;overflow:hidden!important;">
                        <div class="card-body">a</div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card" style="border-radius:5px!Important;overflow:hidden!important;">
                        <div class="card-body">a</div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <div class="card" style="border-radius:5px!Important;overflow:hidden!important;">
                        <div class="card-header">
                            <h6 style="font-size:14px;"><i class="fadeIn animated bx bx-shopping-bag"></i> Keranjang</h6>
                        </div>
                        <div class="card-body">
                            <div>
                                <div class="form-group">
                                    <div class="col-md-12 mb-3">
                                        <select class="form-control" id="produk-search"></select>
                                    </div>
                                </div>
                                {{-- produk order list --}}
                                <template :key="prod.id" x-for="prod in produkOrder">
                                    <div class="card" x-show="produkOrder.length > 0" style="border: none;border-radius: 0px !important;border-bottom: 1px dashed rgba(0, 0, 0, 0.2);box-shadow:none;">
                                        <div class="card-body" style="padding: 5px !important;border-radius: 0px !important;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <strong class="text-primary" style="font-size:14px;" x-text="prod.name"></strong>
                                                    <div class="flex flex-col">
                                                        <div class="block">
                                                            <select class="border border-1 border-gray-300 py-1 px-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500" x-on:change="hndelChangeUnit(prod.id,$event.target.value)">
                                                                <option value="">Satuan</option>
                                                                <template x-for="unitPrice in prod.unitPriceList">
                                                                    <option :selected="prod.satuan_active.id == unitPrice.id" :value="unitPrice.id" x-text="`${unitPrice.unit_name}`">
                                                                </template>
                                                            </select>
                                                            
                                                            
                                                        </div>
                                                        <div class="flex items-center">
                                                            @<span class="" class="text-sm text-red-500 text-gray-500 mr-5" x-text="formatRupiah(prod.price)"></span>
                                                            <small class="text-sm text-gray-300 mr-5 ml-5" style="font-size:10px!important" x-text="`-${prod.discount}%`">0</small>
                                                            <del class="text-sm text-gray-300 mr-5" style="font-size:10px!important" x-text="formatRupiah(prod.price_actual)"></del>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-2">
                                                    <div class="flex h-full justify-center items-center">
                                                        <div class="flex items-center space-x-4">
                                                            <div class="flex items-center border border-gray-300 ">
                                                                <button @click="decrementQty(prod.id)" class="px-2 py-1">-</button>
                                                                <input class="w-12 text-center border-l border-r border-gray-300" readonly type="text" value="1" x-model="prod.qty">
                                                                <button @click="incrementQty(prod.id)" class="px-2 py-1">+</button>
                                                            </div>
                                                            <div class="text-lg font-semibold text-gray-600">
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-4 ">
                                                    <div class="relative" style="position: absolute;bottom: 14px;right: 0px;">
                                                        <button @click="removeFromProdOrder(prod.id)" class="text-white hover:text-red-500 w-[30px] h-[30px] bg-red-300 hover:bg-red-600 shadow-lg border-red-200 border-1 absolute top-[-15px] right-[-15px]">
                                                            <div class="h-full w-full flex justify-center items-center">
                                                                <i class="fadeIn animated bx bx-x"></i>
                                                            </div>
                                                        </button>
                                                    </div>
                                                    <div class="flex h-full items-center justify-end">
                                                        <div class="flex h-full items-center justify-end">
                                                            <span class="text-xl font-bold text-red-500 text-gray-500 mr-5" x-text="formatRupiah(prod?.subTotal ?? 0)"></span>
                                                            {{-- <small class="text-sm text-gray-500 mr-5"
                                                                    x-text="`-${prod.}%`">0</small> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                {{-- produk order list --}}

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="card" style="border-radius:5px!important; overflow:hidden!important;">
                        <div class="card-body" style="padding:0px;">
                            <div class="row">
                                <div class="col-sm-12">

                                    <div class="flex flex-col items-center" x-show="listPayType.length > 0">
                                        <select class="form-control form-control-sm " x-model="payTypeActived" style="padding:10px;color:#fff; border-radius:0px; border:none; background:#34b77e;" x-on:change="((e)=>hndelPayTypeChange(e))">
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <template :key="item.id" x-for="item in listPayType">
                                                <option :selected="item.id == payTypeActived" :value="item.id" x-text="item.name"></option>
                                            </template>
                                        </select>
                                        <i class="bx bx-chevron-down" style="position:absolute; right:5px; color:#fff; font-size:20px; margin-top:5px"></i>
                                    </div>

                                    <div style="padding:10px;">

                                        <div class="flex justify-between mb-2 py-1">
                                            <div>
                                                <h4 class="text-sm text-gray-600">Subtotal</h4>
                                            </div>
                                            <div>
                                                <span class="text-sm text-gray-900" x-text="formatRupiah(ringkasanTotal)"></span>
                                            </div>
                                        </div>
                                        <div class="pt-2 pb-2">
                                            <div class="flex justify-between items-center mb-3">
                                                <div class="flex items-center">
                                                    <input class="border border-1 border-gray-300  py-1 px-1 w-20" type="number" x-on:keyup="((e)=>discountChangeKeyup(e))" placeholder="Discount"/>
                                                    <span class="ml-2 text-lg">%</span>
                                                </div>
                                                <h2 class=" text-sm" x-text="formatRupiah(diskonIdr)">0</h2>
                                            </div>

                                            <div class="flex justify-between items-center mb-3 d-none">
                                                <div class="flex items-center">
                                                    <input checked class="form-checkbox h-3 w-3 text-blue-600 mr-3" type="checkbox" x-on:change="((e)=>{
                                                                unCheckPph(e.target.checked)
                                                            })" />
                                                    <div class="text-sm">pph (<span x-text="pph"></span>)%
                                                    </div>
                                                </div>
                                                <h2 class="text-sm" x-text="formatRupiah(pphIdr)">0</h2>
                                            </div>

                                            <div class="flex justify-between mb-2 py-1">
                                                <div>
                                                    <h4 class="text-lg font-semibold text-gray-600">Total</h4>
                                                </div>
                                                <div>
                                                    <span class="text-lg font-semibold text-gray-600" x-text="formatRupiah(subTotalBuyer)"></span>
                                                </div>
                                            </div>
                                        </div>

                                        
                                    </div>

                                    <div style="position:relative; padding:10px; background:#ffde68; border-radius:0px; display:block">
                                        <div class="form-group">
                                            <div class="col-md-12 mb-3">
                                                <label for="">Pelanggan</label>
                                                <select class="user-search form-control" style=" padding:15px!important"></select>
                                            </div>
                                        </div>
                                        
                                        <div x-show="payTypeActived == 3">
                                            <div class="flex justify-between mb-2 py-1">
                                                <div>
                                                    {{--<h4 class="text-lg font-semibold text-gray-600">PIN</h4>--}}
                                                </div>
                                                <div>
                                                    <input class="border border-1 border-red-500  py-1 px-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500" style="width:100px!important;  border-radius:5px;  border: 2px solid #95d7a8 !important;width:140px;;" placeholder="P I N" type="password" x-model="pinOncardPayment">
                                                </div>
                                            </div>
                                        </div>
                                        <div x-show="payTypeActived == 1">
                                            <div class="flex justify-between mb-2 py-1">
                                                <div>
                                                    <h4 class="text-sm text-gray-600">Bayar</h4>
                                                </div>
                                                <div>
                                                    <input class="border border-1 border-red-500 shadow  py-1 px-1 focus:outline-none focus:ring-blue-500 focus:border-blue-500" id="rupiah" placeholder="Masukan jumlah pembayaran" type="text" x-on:keyup="((e)=>{
                                                                pembayaranTunai(deconvertRp(e.target.value))
                                                            })">
                                                </div>
                                            </div>
                                            <div class="flex justify-between mb-2 py-1">
                                                <div>
                                                    <h4 class="text-sm text-gray-600">
                                                        Kembalian</h4>
                                                </div>
                                                <div>
                                                    <span class="text-lg font-semibold text-gray-600" x-text="formatRupiah(kembalian) ?? `Rp.0`">Rp. 0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div style="position:relative; background:#e6f9ff; border-radius:2px; display:block">
                                        <div class="flex justify-between pl-4">
                                            <div>
                                                <button @click="resetData" class="text-black font-bold py-2 px-4" style="border-radius:0px;">
                                                    Batal
                                                </button>
                                                <i class="bx bx-x text-black" style="position:absolute; left:10px; color:#fff; margin-right:0px; font-size:20px; margin-top:2px"></i>
                                            </div>

                                            <div>
                                                <button @click="trxSubmit" id="btnSubmit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-20 text-center" style="border-bottom-left-radius:100px!important;border-top-left-radius:100px!important;padding-right:35px!important; border-radius:0px;">
                                                    CHECKOUT 
                                                </button>
                                                <i class="bx bx-chevron-right" style="position:absolute; right:0px; color:#fff; margin-right:10px; font-size:20px; margin-top:2px"></i>
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
    </div>
    {{-- <pre x-text="JSON.stringify(produkOrder, null, 2)"></pre> --}}
</div>
@endsection