<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pengaduan Baru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
        }
        .info-box {
            background-color: #f0f7ff;
            border-left: 4px solid #1e40af;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .info-row {
            display: flex;
            margin-bottom: 8px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #1e40af;
        }
        .info-value {
            flex: 1;
            word-break: break-word;
        }
        .action-buttons {
            text-align: center;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 20px;
            margin: 0 5px;
            background-color: #1e40af;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #1d3a8a;
        }
        .footer {
            background-color: #f9fafb;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #ddd;
        }
        .important {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 12px;
            margin: 10px 0;
            border-radius: 4px;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔔 Pengaduan Masyarakat Baru</h1>
            <p>Portal Inspektorat Papua Tengah</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Halo {{ $admin->name }},</p>

            <p>Ada pengaduan masyarakat baru yang masuk ke sistem. Berikut adalah detailnya:</p>

            <!-- Pengaduan Information -->
            <div class="info-box">
                <div class="section-title">📋 Informasi Pengaduan</div>

                <div class="info-row">
                    <span class="info-label">ID Pengaduan</span>
                    <span class="info-value">#{{ $pengaduan->id }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Waktu Masuk</span>
                    <span class="info-value">{{ $pengaduan->created_at->format('d M Y H:i') }} WIT</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <strong style="color: #f59e0b;">{{ ucfirst($pengaduan->status) }}</strong>
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Kategori</span>
                    <span class="info-value">{{ ucfirst($pengaduan->kategori) }}</span>
                </div>
            </div>

            <!-- Pelapor Information -->
            <div class="section">
                <div class="section-title">👤 Data Pelapor</div>

                @if($pengaduan->is_anonymous)
                    <div class="important">
                        <strong>ℹ️ Pengaduan Anonim</strong><br>
                        Pelapor melakukan pengaduan secara anonim.
                    </div>
                @else
                    <div class="info-row">
                        <span class="info-label">Nama</span>
                        <span class="info-value">{{ $pengaduan->nama_pengadu }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $pengaduan->email }}</span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">Telepon</span>
                        <span class="info-value">{{ $pengaduan->telepon ?? '-' }}</span>
                    </div>
                @endif
            </div>

            <!-- Pengaduan Details -->
            <div class="section">
                <div class="section-title">📝 Isi Pengaduan</div>

                <div class="info-box">
                    <p><strong>Subjek:</strong></p>
                    <p>{{ $pengaduan->subjek }}</p>

                    <hr style="border: none; border-top: 1px solid #ccc; margin: 10px 0;">

                    <p><strong>Detail:</strong></p>
                    <p>{{ $pengaduan->isi_pengaduan }}</p>
                </div>
            </div>

            @if($pengaduan->bukti_files && count($pengaduan->bukti_files) > 0)
            <!-- Attachments -->
            <div class="section">
                <div class="section-title">📎 Lampiran</div>
                <ul>
                    @foreach($pengaduan->bukti_files as $file)
                        <li>{{ basename($file) }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ url('/admin/pengaduan/' . $pengaduan->id) }}" class="btn">
                    Lihat Detail Pengaduan
                </a>
            </div>

            <!-- Important Notice -->
            <div class="important">
                <strong>⚠️ Segera Ditindaklanjuti</strong><br>
                Pengaduan ini memerlukan perhatian Anda. Silakan login ke dashboard admin untuk memberikan tanggapan.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                Ini adalah email otomatis dari Portal Inspektorat Papua Tengah.<br>
                Jangan membalas email ini. Masuk ke dashboard untuk memberikan tanggapan.
            </p>
            <p style="margin-top: 10px; color: #999;">
                © {{ date('Y') }} Inspektorat Provinsi Papua Tengah. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</body>
</html>

