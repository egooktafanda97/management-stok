@extends('Template.layout')
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <h6 class="mb-0 text-uppercase">LAPORAN BARANG MASUK</h6>
            <hr />
            <div class="card border-top border-0 border-4 border-primary">
                <div class="card-body p-5">
                    <div class="card-title d-flex align-items-center">
                        <div>
                            <h5 class="mb-0">FILTER BY DATE RANGE</h5>
                        </div>
                    </div>
                    <hr>
                    {{-- filter --}}
                    <form action="" method="GET">
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="start_date">Start Date</label>
                                <input class="form-control" id="start_date" name="start_date" type="date"
                                    value="{{ request('start_date') }}">
                            </div>
                            <div class="col">
                                <label class="form-label" for="end_date">End Date</label>
                                <input class="form-control" id="end_date" name="end_date" type="date"
                                    value="{{ request('end_date') }}">
                            </div>
                        </div>
                        <button class="btn btn-sm btn-success" type="submit">
                            <i class='bx bx-filter'></i> FILTER
                        </button>
                    </form>
                </div>
            </div>

            {{-- Tombol cetak --}}
            <button class="btn btn-sm btn-warning mt-3" onclick="printTable()">
                <i class='bx bxs-printer'></i> PRINT
            </button>

            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Log Barang Masuk</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="printTable">
                            <thead>
                                <tr>
                                    <th>Toko</th>
                                    <th>Produk</th>
                                    <th>Supplier</th>
                                    <th>Harga Beli</th>
                                    <th>Jumlah Barang Masuk</th>
                                    <th>Jumlah Barang</th>
                                    <th>Tanggal Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logBarangMasuks as $logBarangMasuk)
                                    <tr>
                                        <td>{{ $logBarangMasuk->gudang->nama }}</td>
                                        <td>{{ $logBarangMasuk->produk->name }}</td>
                                        <td>{{ $logBarangMasuk?->supplier?->name ?? 'Tidak ada supplier' }}</td>
                                        <td>{{ number_format($logBarangMasuk->harga_beli, 0) }}</td>
                                        <td>{{ $logBarangMasuk->jumlah_barang_masuk }}</td>
                                        <td>{{ $logBarangMasuk->jumlah_barang_masuk + $logBarangMasuk->jumlah_sebelumnya }}
                                        </td>
                                        <td>{{ $logBarangMasuk->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk preview cetak --}}
    <div aria-hidden="true" class="modal fade" id="printModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Preview Cetak Laporan</h5>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <div id="printContent">
                        {{-- Konten akan diisi oleh JavaScript --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Tutup</button>
                    <button class="btn btn-primary" onclick="window.print()" type="button">Cetak</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Style untuk tampilan cetak */
        @media print {
            @page {
                margin: 1cm !important;
                padding: 0 !important size: portrait;
            }

            body {
                margin: 0 !important;
                padding: 0 !important;
            }

            body * {
                visibility: hidden;
                margin: 0;
                padding: 0;
            }

            #print-section,
            #print-section * {
                visibility: visible;
            }

            #print-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 0;
                font-family: 'Arial', sans-serif;
                color: #000;
                background: #fff;
                font-size: 12px;
            }

            .no-print {
                display: none !important;
            }

            .print-header {
                text-align: center;
                margin-bottom: 12px;
                padding-bottom: 8px;
                border-bottom: 1px solid #333;
            }

            .print-header h2 {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 4px;
                color: #000;
                text-transform: uppercase;
            }

            .print-header p {
                margin: 2px 0;
                font-size: 11px;
            }

            .print-table {
                width: 100%;
                border-collapse: collapse;
                margin: 8px 0;
                font-size: 10px;
                page-break-inside: auto;
            }

            .print-table th,
            .print-table td {
                padding: 5px 6px;
                text-align: left;
                border: 1px solid #ddd;
            }

            .print-table th {
                background-color: #f0f0f0;
                color: #000;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 10px;
            }

            .print-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .print-footer {
                margin-top: 12px;
                padding-top: 8px;
                border-top: 1px solid #ddd;
                text-align: right;
                font-size: 10px;
                color: #666;
            }

            .text-right {
                text-align: right;
            }

            .company-info {
                margin-bottom: 10px;
                text-align: center;
            }

            .company-info h3 {
                font-size: 14px;
                font-weight: bold;
                margin-bottom: 3px;
            }

            .company-info p {
                margin: 1px 0;
                font-size: 10px;
            }

            /* Menghilangkan tautan URL pada cetakan */
            a:link:after,
            a:visited:after {
                content: "";
            }
        }

        /* Style untuk modal preview */
        #printContent {
            font-family: Arial, sans-serif;
            padding: 15px;
            background: white;
            font-size: 12px;
        }

        #printContent .print-header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #333;
        }

        #printContent .print-header h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        #printContent .print-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 11px;
        }

        #printContent .print-table th,
        #printContent .print-table td {
            padding: 5px 6px;
            text-align: left;
            border: 1px solid #ddd;
        }

        #printContent .print-table th {
            background-color: #f0f0f0;
            color: #000;
            font-weight: bold;
        }

        #printContent .print-footer {
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>

    <script>
        function printTable() {
            // Ambil data dari filter
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            // Format tanggal untuk ditampilkan
            const formatDate = (dateString) => {
                if (!dateString) return 'Semua Tanggal';
                const date = new Date(dateString);
                return date.toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
            };

            // Buat konten untuk dicetak
            const printContent = `
                <div id="print-section">
                    <div class="print-header">
                        <h2>LAPORAN BARANG MASUK</h2>
                        <p>Periode: ${formatDate(startDate)} - ${formatDate(endDate)}</p>
                        <p>Tanggal Cetak: ${new Date().toLocaleDateString('id-ID', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        })}</p>
                    </div>
                    
                    <table class="print-table">
                        <thead>
                            <tr>
                                <th>Toko</th>
                                <th>Produk</th>
                                <th>Supplier</th>
                                <th>Harga Beli</th>
                                <th>Jml Masuk</th>
                                <th>Jml Barang</th>
                                <th>Tanggal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logBarangMasuks as $logBarangMasuk)
                            <tr>
                                <td>{{ $logBarangMasuk->gudang->nama }}</td>
                                <td>{{ $logBarangMasuk->produk->name }}</td>
                                <td>{{ $logBarangMasuk?->supplier?->name ?? 'Tidak ada supplier' }}</td>
                                <td>Rp {{ number_format($logBarangMasuk->harga_beli, 0, ',', '.') }}</td>
                                <td>{{ $logBarangMasuk->jumlah_barang_masuk }}</td>
                                <td>{{ $logBarangMasuk->jumlah_barang_masuk + $logBarangMasuk->jumlah_sebelumnya }}</td>
                                <td>{{ $logBarangMasuk->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    <div class="print-footer">
                        <p>Dicetak pada: ${new Date().toLocaleString('id-ID')}</p>
                        <p>Oleh: {{ Auth::user()->name ?? 'Administrator' }}</p>
                    </div>
                </div>
            `;

            // Tampilkan preview di modal
            document.getElementById('printContent').innerHTML = printContent;
            const printModal = new bootstrap.Modal(document.getElementById('printModal'));
            printModal.show();
        }
    </script>
@endsection
