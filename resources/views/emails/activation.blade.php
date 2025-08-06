<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aktivasi Akun Anda</title>
</head>
<body style="font-family: sans-serif; background-color: #f9fafb; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); padding: 30px;">
        <h2 style="font-size: 20px; font-weight: bold; color: #1f2937;">Halo, {{ $user->name }}</h2>
        <p style="font-size: 14px; color: #374151;">
            Anda telah didaftarkan pada sistem absensi. Berikut detail akun Anda:
        </p>

        <ul style="font-size: 14px; color: #1f2937; margin-top: 10px;">
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Password:</strong> {{ $password }}</li>
        </ul>

<p style="font-size: 14px; color: #374151; margin-top: 20px;">
    Untuk mulai menggunakan akun Anda, silakan aktivasi terlebih dahulu dengan mengklik tombol berikut:
</p>


        <div style="margin: 30px 0; text-align: center;">
            <a href="{{ url('/activate/' . $user->activation_token) }}"
               style="background-color: #2563eb; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-size: 14px;">
                Aktivasi Akun
            </a>
        </div>

        <p style="font-size: 13px; color: #6b7280;">
            Jika Anda tidak merasa mendaftarkan akun ini, Anda dapat mengabaikan email ini.
        </p>

        <hr style="margin-top: 30px; border: none; border-top: 1px solid #e5e7eb;">
        <p style="font-size: 12px; color: #9ca3af; text-align: center;">
            &copy; {{ date('Y') }} Sistem Absensi STSD. Semua Hak Dilindungi.
        </p>
    </div>
</body>
</html>
