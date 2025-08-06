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
                <span class="font-bold text-xl">ADMIN</span>
            </div>
            <nav class="flex-1 p-4 space-y-2 text-sm">
    <a href="{{ route('admin.board') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
        <span class="material-icons mr-2">dashboard</span> Dashboard
    </a>

    <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Biodata</p>
        <a href="{{ route('admin.manage-juruarah') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
            <span class="material-icons mr-2">person</span> Juru Arah
        </a>
        <a href="{{ route('admin.manage-anggota') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
            <span class="material-icons mr-2">person_outline</span> Anggota
        </a>

    <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Absensi</p>
    <a href="{{ route('admin.attendance') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
        <span class="material-icons mr-2">fact_check</span> Absensi
    </a>
    <a href="{{ route('admin.rekap.index') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
    <span class="material-icons mr-2">table_view</span> Rekap Absensi
</a>

    <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Saya</p>
    </a>
    <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
        <span class="material-icons mr-2">account_circle</span> Profil
    </a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center w-full text-left px-4 py-2 hover:bg-gray-700 rounded">
            <span class="material-icons mr-2">logout</span> Logout
        </button>
    </form>
</nav>

            <div class="p-4 text-xs text-gray-400 border-t border-gray-700">
                Copyright © 2024 <span class="text-blue-400">ST Satya Dharma</span>.<br>Created by <span class="text-blue-400">Wahyu</span>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6 overflow-auto">
    <header class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">@yield('title')</h1>
        <div class="flex items-center space-x-4">
            <span class="material-icons">notifications</span>
            <span class="font-semibold">{{ Auth::user()->name }}</span>

            {{-- Jika menggunakan gravatar --}}
            <img src="{{ 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?d=mp' }}"
                class="w-8 h-8 rounded-full" alt="User" />

            {{-- Jika punya field foto di database seperti Auth::user()->photo --}}
            {{-- <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="w-8 h-8 rounded-full" alt="User" /> --}}
        </div>
    </header>

    @yield('content')
</main>
    </div>
    @stack('chart-script')
</body>
</html>
