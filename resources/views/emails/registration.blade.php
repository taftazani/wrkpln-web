<!DOCTYPE html>
<html>

<head>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f9;
    }

    .container {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header h1 {
        color: #4CAF50;
    }

    .content {
        line-height: 1.6;
    }

    .credentials {
        margin: 20px 0;
    }

    .credentials h3 {
        margin-bottom: 10px;
    }

    .credentials p {
        margin: 0;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        color: #888;
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Selamat Datang di Layanan Kami, {{ $user->name }}!</h1>
        </div>
        <div class="content">
            <p>Hai {{ $user->name }},</p>
            <p>Terima kasih telah mendaftarkan perusahaan Anda, {{ $company->name }} dengan kami. Kami sangat senang
                Anda bergabung. Berikut adalah detail pendaftaran Anda:</p>
            <div class="credentials">
                <h3>Detail Akun Anda:</h3>
                <p><strong>ID Pengguna:</strong> {{ $credentials['user_id'] }}</p>
                <p><strong>Email:</strong> {{ $credentials['email'] }}</p>
                <p><strong>Password:</strong> {{ $credentials['password'] }}</p>
                <p><strong>Kode Perusahaan:</strong> {{ $company->company_code }}</p>
            </div>
            <p>Jika Anda memiliki pertanyaan, jangan ragu untuk menghubungi tim dukungan kami.</p>
            <p>Salam hangat,</p>
            <p>Tim Perusahaan</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Workplan. All rights reserved.</p>
        </div>
    </div>
</body>

</html>