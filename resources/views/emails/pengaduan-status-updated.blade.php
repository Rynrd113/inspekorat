<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status Pengaduan</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .card { background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden; }
        .header { background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .header p { margin: 10px 0 0; opacity: 0.9; font-size: 14px; }
        .content { padding: 30px; }
        .greeting { font-size: 16px; margin-bottom: 20px; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-diterima { background: #fef3c7; color: #92400e; }
        .status-proses { background: #dbeafe; color: #1e40af; }
        .status-selesai { background: #d1fae5; color: #065f46; }
        .status-ditolak { background: #fee2e2; color: #991b1b; }
        .info-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-row { display: flex; margin-bottom: 12px; }
        .info-row:last-child { margin-bottom: 0; }
        .info-label { flex: 0 0 140px; font-weight: 600; color: #475569; font-size: 14px; }
        .info-value { flex: 1; color: #1e293b; font-size: 14px; }
        .divider { height: 1px; background: #e2e8f0; margin: 20px 0; }
        .description-box { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .description-label { font-weight: 600; color: #475569; margin-bottom: 10px; display: block; }
        .description-content { color: #334155; white-space: pre-wrap; }
        .response-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .response-label { font-weight: 600; color: #166534; margin-bottom: 10px; display: block; }
        .response-content { color: #166534; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { margin: 0; font-size: 12px; color: #64748b; }
        .footer a { color: #3b82f6; text-decoration: none; }
        .btn { display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 500; margin-top: 20px; }
        .btn:hover { background: #2563eb; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>📋 Update Status Pengaduan</h1>
                <p>Inspektorat Provinsi Papua Tengah</p>
            </div>
            
            <div class="content">
                <p class="greeting">Yth. <strong>{{ $pengaduan->nama_pengadu }}</strong>,</p>
                
                <p>Kami informasikan bahwa status pengaduan Anda telah diperbarui:</p>
                
                <div class="info-box">
                    <div class="info-row">
                        <span class="info-label">Nomor Tiket:</span>
                        <span class="info-value"><strong>#{{ $pengaduan->id }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Subjek:</span>
                        <span class="info-value">{{ $pengaduan->subjek }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kategori:</span>
                        <span class="info-value">{{ ucfirst($pengaduan->kategori) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Pengaduan:</span>
                        <span class="info-value">{{ $pengaduan->tanggal_pengaduan->format('d M Y H:i') }} WIT</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status Sebelumnya:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $oldStatus }}">{{ ucfirst($oldStatus) }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status Saat Ini:</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $newStatus }}">{{ ucfirst($newStatus) }}</span>
                        </span>
                    </div>
                </div>

                @if($pengaduan->tanggapan)
                <div class="response-box">
                    <span class="response-label">📝 Tanggapan dari Inspektorat:</span>
                    <div class="response-content">{{ $pengaduan->tanggapan }}</div>
                </div>
                @endif

                <div class="divider"></div>

                <p><strong>Arti Status:</strong></p>
                <ul style="padding-left: 20px; margin: 10px 0;">
                    <li><span class="status-badge status-diterima">Diterima</span> - Pengaduan telah diterima oleh inspektorat</li>
                    <li><span class="status-badge status-proses">Proses</span> - Pengaduan sedang ditindaklanjuti oleh tim inspektorat</li>
                    <li><span class="status-badge status-selesai">Selesai</span> - Pengaduan telah selesai ditangani</li>
                    <li><span class="status-badge status-ditolak">Ditolak</span> - Pengaduan ditolak setelah evaluasi</li>
                </ul>

                @if($newStatus !== 'selesai' && $newStatus !== 'ditolak')
                <p>Anda dapat mengecek perkembangan pengaduan lebih lanjut melalui website kami.</p>
                @else
                <p>Terima kasih atas partisipasi Anda dalam menjaga integritas pelayanan publik.</p>
                @endif

                <div style="text-align: center; margin-top: 30px;">
                    <a href="{{ url('/pengaduan/cek-status') }}?ticket={{ $pengaduan->id }}" class="btn">Cek Status Pengaduan</a>
                </div>
            </div>

            <div class="footer">
                <p>Ini adalah email otomatis, mohon tidak membalas email ini.</p>
                <p>Jika Anda memiliki pertanyaan, silakan hubungi kami melalui <a href="mailto:{{ config('contact.email') }}">{{ config('contact.email') }}</a></p>
                <p>&copy; {{ date('Y' ) }} Inspektorat Provinsi Papua Tengah. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>