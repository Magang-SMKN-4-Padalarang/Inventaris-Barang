<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Data Laporan</title>
    <style>
        @page { size: 60mm 35mm; margin: 0; }

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

        .stiker {
            width: 60mm;
            height: 35mm;
            margin: 10px auto;
            padding: 0;
            background: #fff;
            border: 0.4mm solid #0b2447;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow: hidden;
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
            .stiker { margin: 0; border: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button onclick="window.print()">Cetak</button>
        <a href="#" onclick="window.close(); return false;">Batal</a>
    </div>

    <div class="stiker">
        <div class="judul">Data Laporan</div>

        <div class="isi">
            <div class="nama">{{ $barang->nama_barang }}</div>
            <svg id="barcode"></svg>
        </div>

        <div class="aksen"></div>
    </div>

    <script src="{{ asset('vendor/jsbarcode/JsBarcode.all.min.js') }}"></script>
    <script>
        // Kode baru lebih panjang (mis. PPLG1-LMR1-ELK-0001). CODE128 butuh ±11 modul per
        // karakter, jadi tinggi garis & ukuran tulisan dinaikkan mengikuti panjang kode
        // supaya setelah diperkecil ke 54mm tetap jelas dan bisa di-scan.
        const kode = @json($barang->kode_barcode);
        const faktor = Math.max(1, (kode.length * 11 + 35) / 123);

        JsBarcode('#barcode', kode, {
            format: 'CODE128',
            width: 2,
            height: Math.round(50 * faktor),
            displayValue: true,
            fontSize: Math.round(14 * faktor),
            margin: 0,
            lineColor: '#0b2447',
        });

        window.addEventListener('afterprint', () => {
            if (!confirm('Apakah barcode sudah berhasil dicetak?')) return;

            fetch(@json(route('barang.cetak.catat', $barang->id_barang)), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                keepalive: true,
            });
        });

        window.addEventListener('load', () => setTimeout(() => window.print(), 300));
    </script>
</body>
</html>