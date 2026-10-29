<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cetak Barcode ({{ $jumlah }} barang)</title>
    <style>
        /* Satu lembar A4: 3 kolom x 7 baris stiker 60x35 mm (21 per lembar) */
        @page { size: A4; margin: 10mm; }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #e8eef9;
        }

        .toolbar {
            padding: 12px;
            text-align: center;
        }

        .toolbar .info {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #0b2447;
        }

        .toolbar button, .toolbar a {
            padding: 8px 16px;
            margin: 0 4px;
            border: 1px solid #0b2447;
            background: #0b2447;
            color: #f1b53d;
            border-radius: 6px;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .toolbar a { background: #fff; color: #0b2447; }

        /* Pratinjau kertas di layar */
        .lembar {
            width: 210mm;
            padding: 10mm;
            margin: 12px auto;
            background: #fff;
            box-shadow: 0 2px 10px rgba(11, 36, 71, .25);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 60mm);
            gap: 4mm 5mm;
            justify-content: center;
            align-content: start;
        }

        .stiker {
            width: 60mm;
            height: 35mm;
            background: #fff;
            border: 0.3mm solid #0b2447; /* garis bantu untuk memotong */
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
            break-inside: avoid;
        }

        .stiker .judul {
            width: 100%;
            padding: 1.2mm 0;
            background: #0b2447;
            color: #f1b53d;
            font-size: 7pt;
            font-weight: bold;
            letter-spacing: .25em;
            text-transform: uppercase;
        }

        .stiker .isi {
            flex: 1;
            width: 100%;
            padding: 1mm 2mm;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: .6mm;
        }

        .stiker .nama {
            font-size: 9pt;
            font-weight: bold;
            color: #0b2447;
            max-width: 100%;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .stiker svg { width: 54mm; height: 18mm; }

        .stiker .aksen {
            width: 100%;
            height: 1.5mm;
            background: #f1b53d;
        }

        @media print {
            body { background: #fff; }
            .toolbar { display: none; }

            .lembar {
                width: auto;
                padding: 0;
                margin: 0;
                box-shadow: none;
                break-after: page;
                page-break-after: always;
            }
            .lembar:last-of-type {
                break-after: auto;
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <span class="info">{{ $jumlah }} stiker &bull; {{ $lembar->count() }} lembar A4 (maks. 21 stiker per lembar)</span>
        <button type="button" onclick="window.print()">Cetak</button>
        <a href="#" onclick="window.close(); return false;">Batal</a>
    </div>

    @foreach ($lembar as $halaman)
        <section class="lembar">
            <div class="grid">
                @foreach ($halaman as $item)
                    <div class="stiker">
                        <div class="judul">Data Laporan</div>

                        <div class="isi">
                            <div class="nama">{{ $item->nama_barang }}</div>
                            <svg class="barcode" data-kode="{{ $item->kode_barcode }}"></svg>
                        </div>

                        <div class="aksen"></div>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach

    <script src="{{ asset('vendor/jsbarcode/JsBarcode.all.min.js') }}"></script>
    <script>
        document.querySelectorAll('svg.barcode').forEach(el => {
            JsBarcode(el, el.dataset.kode, {
                format: 'CODE128',
                width: 2,
                height: 50,
                displayValue: true,
                fontSize: 14,
                margin: 0,
                lineColor: '#0b2447',
            });
        });

        const ids = @json($ids);

        window.addEventListener('afterprint', () => {
            if (!confirm('Apakah barcode sudah berhasil dicetak?')) return;

            fetch(@json(route('barang.cetak-massal.catat')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ ids }),
                keepalive: true,
            });
        });

        window.addEventListener('load', () => setTimeout(() => window.print(), 300));
    </script>
</body>
</html>
