<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - AZCLIP</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            text-align: center;
        }
        .container {
            max-width: 400px;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
        .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        h1 {
            color: #0f172a;
            margin: 0 0 0.5rem;
            font-size: 1.5rem;
        }
        p {
            color: #64748b;
            margin: 0 0 1.5rem;
            line-height: 1.5;
        }
        button {
            background-color: #4f46e5;
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #4338ca;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">📶❌</div>
        <h1>Anda Sedang Offline</h1>
        <p>Sepertinya Anda kehilangan koneksi internet. Silakan periksa jaringan Anda lalu coba lagi.</p>
        <button onclick="window.location.reload()">Coba Lagi</button>
    </div>
</body>
</html>
