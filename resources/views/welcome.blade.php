<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Absensi STSD</title>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 font-sans">
    <div class="flex flex-col min-h-screen items-center justify-center">
        <div class="bg-white rounded-2xl shadow-xl px-8 py-10 max-w-md w-full text-center mb-10">
            <h1 class="text-3xl md:text-4xl font-bold text-indigo-800 mb-3">Selamat Datang di Absensi STSD</h1>
            <p class="text-gray-600 text-lg mb-6">Sistem absensi digital modern untuk STSD. Mudah digunakan, aman, dan realtime.</p>
            @auth
                <a href="{{ url('/dashboard') }}" class="inline-block bg-gradient-to-r from-indigo-800 to-red-500 text-white font-semibold px-8 py-3 rounded-lg shadow hover:from-red-500 hover:to-indigo-800 transition mb-2">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="inline-block bg-gradient-to-r from-indigo-800 to-red-500 text-white font-semibold px-8 py-3 rounded-lg shadow hover:from-red-500 hover:to-indigo-800 transition mb-2">Masuk</a>
            @endauth
        </div>
        <div class="flex flex-wrap gap-6 justify-center mb-10 w-full max-w-4xl">
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border border-indigo-100 flex-1 min-w-[220px] max-w-xs">
                <svg class="w-10 h-10 mb-3 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="9" cy="11" r="3"/><circle cx="15" cy="11" r="3"/><path d="M6 19v-1a4 4 0 014-4h4a4 4 0 014 4v1"/></svg>
                <h3 class="font-semibold text-lg mb-2 text-indigo-800">Mudah Digunakan</h3>
                <p class="text-gray-600 text-sm">Antarmuka sederhana dan intuitif, siap digunakan oleh semua anggota STSD.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border border-indigo-100 flex-1 min-w-[220px] max-w-xs">
                <svg class="w-10 h-10 mb-3 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 8v4l3 3"/></svg>
                <h3 class="font-semibold text-lg mb-2 text-indigo-800">Realtime &amp; Akurat</h3>
                <p class="text-gray-600 text-sm">Absensi tercatat secara realtime dan dapat dipantau oleh admin kapan saja.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 flex flex-col items-center border border-indigo-100 flex-1 min-w-[220px] max-w-xs">
                <svg class="w-10 h-10 mb-3 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="4" x2="12" y2="20"/><line x1="4" y1="12" x2="20" y2="12"/></svg>
                <h3 class="font-semibold text-lg mb-2 text-indigo-800">Fitur Lengkap</h3>
                <p class="text-gray-600 text-sm">Kelola anggota, sesi absensi, laporan, dan banyak fitur lainnya.</p>
            </div>
        </div>
        <div class="text-center text-gray-500 text-sm mb-4">
            Absensi STSD &copy; {{ date('Y') }}<br>
            <span class="text-xs">Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</span>
        </div>
    </div>
</body>
</html>
