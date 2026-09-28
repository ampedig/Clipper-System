<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penarikan Dana Berhasil - AZCLIP</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            color: #334155;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #dc2626;
            padding: 28px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        .body {
            padding: 36px 30px;
        }
        .body p {
            font-size: 15px;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 20px;
            color: #475569;
        }
        .status-badge {
            display: inline-block;
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 18px;
        }
        .details-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
        }
        .details-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #64748b;
            margin-bottom: 14px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 8px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 14px;
        }
        .detail-label {
            color: #64748b;
        }
        .detail-value {
            font-weight: 600;
            color: #0f172a;
            text-align: right;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 10px 0;
        }
        .total-row {
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }
        .total-value {
            font-size: 18px;
            font-weight: 800;
            color: #dc2626;
            text-align: right;
        }
        .notes-box {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 8px;
            padding: 12px 16px;
            margin-top: 16px;
            font-size: 13px;
            color: #92400e;
            line-height: 1.5;
        }
        .btn-container {
            text-align: center;
            margin: 32px 0 10px 0;
        }
        .btn {
            display: inline-block;
            background-color: #dc2626;
            color: #ffffff !important;
            font-weight: 700;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            font-size: 15px;
        }
        .btn:hover {
            background-color: #b91c1c;
        }
        .footer {
            padding: 24px 30px;
            background-color: #f8fafc;
            border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        .footer p {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 10px 0;
            line-height: 1.6;
        }
        .copyright {
            margin-top: 14px !important;
            font-size: 12px !important;
            color: #94a3b8 !important;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>AZCLIP</h1>
            </div>
            <div class="body">
                <div class="status-badge">
                    ✓ Penarikan Selesai Diproses
                </div>

                <p>Halo <strong>{{ $withdrawal->user->name ?? 'Clipper' }}</strong>,</p>
                <p>Kabar gembira! Permohonan penarikan saldo Anda telah disetujui oleh admin dan dana telah berhasil ditransfer ke rekening tujuan Anda.</p>

                <div class="details-box">
                    <div class="details-title">Rincian Penarikan Dana</div>
                    
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">ID Penarikan</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; text-align: right;">#{{ $withdrawal->id }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Tanggal Selesai</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; text-align: right;">
                                {{ ($withdrawal->processed_at ?? now())->translatedFormat('d F Y, H:i') }} WIB
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Rekening Tujuan</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; text-align: right;">
                                {{ $withdrawal->bank_name }} - {{ $withdrawal->account_number }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Atas Nama</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; text-align: right;">{{ $withdrawal->account_name }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 6px 0;"><div class="divider"></div></td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Nominal Penarikan</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; text-align: right;">Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 6px 0; color: #64748b; font-size: 14px;">Biaya Admin</td>
                            <td style="padding: 6px 0; color: #0f172a; font-size: 14px; font-weight: 600; text-align: right;">
                                {{ $withdrawal->fee > 0 ? 'Rp ' . number_format($withdrawal->fee, 0, ',', '.') : 'Gratis' }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding: 6px 0;"><div class="divider"></div></td>
                        </tr>
                        <tr>
                            <td style="padding: 8px 0; color: #0f172a; font-size: 15px; font-weight: 700;">Total Diterima</td>
                            <td style="padding: 8px 0; color: #dc2626; font-size: 18px; font-weight: 800; text-align: right;">
                                Rp {{ number_format($withdrawal->net_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>

                    @if(!empty($withdrawal->notes))
                        <div class="notes-box">
                            <strong>Catatan Admin:</strong> {{ $withdrawal->notes }}
                        </div>
                    @endif
                </div>

                <div class="btn-container">
                    <a href="{{ route('app.withdrawals.index') }}" class="btn">Lihat Riwayat Penarikan</a>
                </div>

                <p style="margin-top: 25px; margin-bottom: 0;">Salam hangat,<br><strong>Tim AZCLIP</strong></p>
            </div>
            <div class="footer">
                <p>Harap periksa mutasi rekening Anda secara berkala. Jika dana belum masuk dalam 1x24 jam, silakan hubungi tim kami melalui kontak bantuan.</p>
                <p class="copyright">&copy; {{ date('Y') }} AZCLIP. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>
