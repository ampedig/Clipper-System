<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Kata Sandi - AZCLIP</title>
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
            padding: 30px 20px;
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
            padding: 40px 30px;
        }
        .body p {
            font-size: 15px;
            line-height: 1.6;
            margin-top: 0;
            margin-bottom: 20px;
            color: #475569;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
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
        }
        .footer p {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 10px 0;
            line-height: 1.6;
            word-break: break-all;
        }
        .link-text {
            color: #dc2626;
            text-decoration: underline;
        }
        .copyright {
            margin-top: 20px !important;
            font-size: 12px !important;
            text-align: center;
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
                <p>Halo <strong>{{ $user->name ?? 'Pengguna' }}</strong>,</p>
                <p>Anda menerima email ini karena kami menerima permintaan pengaturan ulang kata sandi untuk akun AZCLIP Anda. Jika Anda tidak merasa melakukan permintaan ini, Anda dapat mengabaikan email ini.</p>
                
                <div class="btn-container">
                    <a href="{{ $url }}" class="btn">Atur Ulang Kata Sandi</a>
                </div>

                <p>Tautan reset kata sandi ini akan kedaluwarsa dalam 60 menit.</p>
                <p style="margin-bottom: 0;">Salam hangat,<br><strong>Tim AZCLIP</strong></p>
            </div>
            <div class="footer">
                <p>Jika Anda kesulitan mengklik tombol "Atur Ulang Kata Sandi", salin dan tempel URL di bawah ini ke dalam browser web Anda:</p>
                <p><a href="{{ $url }}" class="link-text">{{ $url }}</a></p>
                <p class="copyright">&copy; {{ date('Y') }} AZCLIP. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </div>
</body>
</html>
