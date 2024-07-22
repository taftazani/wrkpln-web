<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Anda</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .header {
        background-color: #4CAF50;
        color: #ffffff;
        padding: 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        text-align: center;
    }

    .content {
        padding: 20px;
        text-align: center;
    }

    .otp-code {
        font-size: 24px;
        font-weight: bold;
        color: #4CAF50;
        margin: 20px 0;
    }

    .footer {
        background-color: #f4f4f4;
        color: #555555;
        padding: 20px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: center;
    }

    .footer a {
        color: #4CAF50;
        text-decoration: none;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Kode OTP Anda</h1>
        </div>
        <div class="content">
            <p>Pengguna Yth.,</p>
            <p>Gunakan kode OTP berikut untuk menyelesaikan proses masuk Anda:</p>
            <p class="otp-code">{{ $otp }}</p>
            <p>Kode ini akan kedaluwarsa dalam 10 menit. Jika Anda tidak meminta kode ini, harap abaikan email ini.</p>
        </div>
        <div class="footer">
            <p>Terima kasih telah menggunakan layanan kami!</p>
            <p><a href="https://yourwebsite.com">Workplan</a></p>
        </div>
    </div>
</body>

</html>