<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>LAPORAN DATA PRODUK</title>
    <style>
        @page {
            margin: 1cm;
            size: portrait;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
            color: #000;
            background: #fff;
        }

        .print-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #333;
        }

        .print-header h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .print-header p {
            margin: 3px 0;
            font-size: 12px;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
            font-size: 11px;
        }

        .print-table th,
        .print-table td {
            padding: 6px 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .print-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .print-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .print-footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: right;
            font-size: 11px;
            color: #666;
        }

        .no-print {
            display: none !important;
        }

        @media print {
            body * {
                visibility: hidden;
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
                padding: 15px;
            }
        }
    </style>
</head>

<body>
    <div id="print-section">
        <div class="print-header">
            <h2>LAPORAN DATA PRODUK</h2>
            <p>Tanggal Cetak: {{ $printedAt }}</p>
        </div>

        <table class="print-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>JENIS</th>
                    <th>STOK</th>
                    <th>HARGA JUAL</th>
                    <th>DESKRIPSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($produk as $key => $produks)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $produks->name }}</td>
                        <td>{{ $produks->jenisProduk->name }}</td>
                        <td>{{ $produks->stok->jumlah ?? 0 }}
                            {{ $produks->satuanStok->jenisSatuan->name ?? '' }}</td>
                        <td>
                            @if (isset($produks->unitPrieces) && count($produks->unitPrieces) > 0)
                                Rp
                                {{ number_format($produks->unitPrieces[0]->priece, 0, ',', '.') }}/{{ $produks->unitPrieces[0]->jenisSatuanJual->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $produks->deskripsi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="print-footer">
            <p>Dicetak pada: {{ $printedAt }}</p>
            <p>Oleh: {{ $printedBy }}</p>
        </div>
    </div>

    <script>
        // Cetak otomatis ketika halaman dimuat
        window.onload = function() {
            window.print();

            // Redirect kembali setelah cetak (opsional)
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
</body>

</html>
