<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 text-white flex flex-col">
            <div class="p-4 flex items-center justify-center border-b border-gray-700">
                <span class="font-bold text-xl">JURU ARAH</span>
            </div>
            <nav class="flex-1 p-4 space-y-2 text-sm">
                <a href="{{ route('juruarah.board') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
                    <span class="material-icons mr-2">dashboard</span> Dashboard
                </a>
        
                <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Absensi</p>
                <a href="/juruarah/monitoring" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
                    <span class="material-icons mr-2">visibility</span> Monitoring Absensi
                </a>
                <a href="/juruarah/sesi/buka" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
                    <span class="material-icons mr-2">play_circle</span> Buka Sesi Absensi
                </a>
                <a href="/juruarah/sesi/tutup" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
                    <span class="material-icons mr-2">stop_circle</span> Tutup Sesi Absensi
                </a>

                <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Anggota</p>
                <a href="/juruarah/anggota" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
                    <span class="material-icons mr-2">group</span> Daftar Anggota
                </a>

                <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Laporan</p>

                <a href="/juruarah/rekap" class="flex items-center px-4 py-2 hover:bg-green-700 rounded bg-green-600 mt-4 text-white">
                    <span class="material-icons mr-2">table_view</span> Rekap Absensi (Tabel)
                </a>

                <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Saya</p>
                </a>
                <a href="{{ route('juruarah.profile.show') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
                    <span class="material-icons mr-2">account_circle</span> Profil
                </a>

                <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="material-icons mr-2">logout</span> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>

            <div class="p-4 text-xs text-gray-400 border-t border-gray-700">
                Copyright © 2025 <span class="text-blue-400">ST Satya Dharma</span>.<br>Created by <span class="text-blue-400">Wahyu</span>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6 overflow-auto">
            <header class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold">@yield('title')</h1>
                <div class="flex items-center space-x-4">
                    <span class="material-icons">notifications</span>
                    <span class="font-semibold">{{ Auth::user()->name }}</span>
                    <img src="{{ 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?d=mp' }}"
                        class="w-8 h-8 rounded-full" alt="User" />
                </div>
            </header>

            @yield('content')
        </main>
    </div>
</body>

    @stack('scripts')
</html>
