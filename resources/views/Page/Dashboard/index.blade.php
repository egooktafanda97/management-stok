@extends('Template.layout')
@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Barang</p>
                                <h4 class="my-1 text-info">{{ $barangMasukCount ?? 0 }}</h4>
                                <p class="mb-0 font-13"></p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-scooter text-white ms-auto">
                                <i class='bx bxs-package'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Total Pelanggan</p>
                                <h4 class="my-1 text-danger">{{ $pelangganCount ?? 0 }}</h4>
                                <p class="mb-0 font-13"></p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-bloody text-white ms-auto">
                                <i class='bx bxs-group'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Terjual Hari Ini</p>
                                <h4 class="my-1 text-success">{{ $transaksiTodayCount ?? 0 }}</h4>
                                <p class="mb-0 font-13"></p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                <i class='bx bxs-bar-chart-alt-2'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card radius-10 border-start border-0 border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-secondary">Pemasukan Hari Ini</p>
                                <h4 class="my-1 text-warning">{{ $pemasukanTodayCount ??0 }}</h4>
                                <p class="mb-0 font-13"></p>
                            </div>
                            <div class="widgets-icons-2 rounded-circle bg-gradient-blooker text-white ms-auto">
                                <i class='bx bx-money'></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end row-->

        {{--<div class="row">
            <div class="col-12 col-lg-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0">Penjualan Bulanan Tahun {{ Carbon\Carbon::now()->year }}</h6>
                            </div>
                        </div>

                        <div class="chart-container">
                            <canvas id="chartSales" style="height: 300px"></canvas>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        --}}
    </div>
</div>
@endsection
@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

</script>
@endpush