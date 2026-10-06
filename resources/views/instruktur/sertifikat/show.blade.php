{{-- Halaman ini sengaja berdiri sendiri, nggak pakai layout sidebar,
     biar hasil cetaknya bersih. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat — {{ $sertifikat->nama_lengkap }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Space Grotesk', sans-serif;
            background: #d8d3c4;
            padding: 40px 20px;
            color: #1a2a3a;
        }

        .lembar {
            width: 1000px;
            max-width: 100%;
            margin: 0 auto;
            background: #f5f0e1;
            border: 3px solid #1a2a3a;
            box-shadow: 12px 12px 0 #1a2a3a;
            padding: 60px 70px;
            position: relative;
        }

        /* Garis tipis di dalam bingkai utama */
        .lembar::after {
            content: '';
            position: absolute;
            inset: 16px;
            border: 1px solid rgba(26, 42, 58, 0.25);
            pointer-events: none;
        }

        .pita {
            display: inline-block;
            background: #e8a838;
            border: 2px solid #1a2a3a;
            padding: 6px 16px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2em;
        }

        .judul {
            font-family: 'DM Serif Display', serif;
            font-size: 54px;
            line-height: 1.05;
            margin: 28px 0 8px;
        }

        .sub { font-size: 13px; letter-spacing: 0.15em; color: #c8562f; font-weight: 700; }

        .nama {
            font-family: 'DM Serif Display', serif;
            font-size: 46px;
            color: #c8562f;
            border-bottom: 3px solid #1a2a3a;
            display: inline-block;
            padding-bottom: 6px;
            margin: 10px 0 6px;
        }

        .materi {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            line-height: 1.3;
            margin-top: 6px;
        }

        .baris-data {
            display: flex;
            gap: 50px;
            margin-top: 40px;
            padding-top: 28px;
            border-top: 2px solid #1a2a3a;
            flex-wrap: wrap;
        }

        .data-label { font-size: 10px; letter-spacing: 0.15em; font-weight: 700; color: #c8562f; }
        .data-isi   { font-size: 18px; font-weight: 700; margin-top: 4px; }

        .nilai-besar {
            font-family: 'DM Serif Display', serif;
            font-size: 40px;
            color: #7a8f6b;
            line-height: 1;
        }

        .ttd { margin-top: 50px; text-align: right; }
        .ttd-garis {
            display: inline-block;
            border-top: 2px solid #1a2a3a;
            padding-top: 8px;
            min-width: 240px;
            text-align: center;
        }

        .alat { max-width: 1000px; margin: 0 auto 20px; display: flex; gap: 10px; }

        .tombol {
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            padding: 12px 22px;
            background: #1a2a3a;
            color: #f5f0e1;
            border: 2px solid #1a2a3a;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .tombol-terang { background: #f5f0e1; color: #1a2a3a; }

        /* Yang tampil waktu dicetak */
        @media print {
            body { background: #fff; padding: 0; }
            .alat { display: none; }
            .lembar { box-shadow: none; border-width: 2px; width: 100%; }
            @page { size: A4 landscape; margin: 12mm; }
        }
    </style>
</head>
<body>

    <div class="alat">
        <button class="tombol" onclick="window.print()">Cetak / Simpan PDF</button>
        <a class="tombol tombol-terang" href="{{ route('instruktur.sertifikat.index') }}">Kembali</a>
    </div>

    <div class="lembar">
        <div class="pita">SERTIFIKAT KOMPETENSI</div>

        <div class="judul">PPKPI</div>
        <div class="sub">PUSAT PELATIHAN KERJA PENGEMBANGAN INDUSTRI</div>

        <div style="margin-top:46px; font-size:14px; color:#555;">Dengan ini menyatakan bahwa</div>

        <div class="nama">{{ $sertifikat->nama_lengkap }}</div>
        @if ($sertifikat->nis)
            <div style="font-size:12px; color:#888;">NIS {{ $sertifikat->nis }}</div>
        @endif

        <div style="margin-top:26px; font-size:14px; color:#555;">
            telah menyelesaikan dan dinyatakan <strong>LULUS</strong> pada pelatihan
        </div>

        <div class="materi">{{ $sertifikat->judul_materi }}</div>
        <div style="font-size:13px; color:#888; margin-top:4px;">
            Bidang {{ $sertifikat->kategori }}
            @if ($sertifikat->duration_minutes)
                · {{ $sertifikat->duration_minutes }} menit pembelajaran
            @endif
        </div>

        <div class="baris-data">
            <div>
                <div class="data-label">NILAI AKHIR</div>
                <div class="nilai-besar">
                    {{ $sertifikat->nilai !== null ? rtrim(rtrim(number_format($sertifikat->nilai, 1), '0'), '.') : '—' }}
                </div>
            </div>

            <div>
                <div class="data-label">NOMOR SERTIFIKAT</div>
                <div class="data-isi" style="font-size:14px;">{{ $sertifikat->nomor }}</div>
            </div>

            <div>
                <div class="data-label">TANGGAL KELULUSAN</div>
                <div class="data-isi" style="font-size:14px;">
                    {{ $sertifikat->lulus_pada
                        ? \Carbon\Carbon::parse($sertifikat->lulus_pada)->translatedFormat('d F Y')
                        : '—' }}
                </div>
            </div>
        </div>

        <div class="ttd">
            <div class="ttd-garis">
                <div style="font-weight:700;">{{ $sertifikat->nama_instruktur }}</div>
                <div style="font-size:12px; color:#888;">
                    {{ $sertifikat->jabatan_instruktur ?? 'Instruktur' }}
                </div>
            </div>
        </div>
    </div>

</body>
</html>