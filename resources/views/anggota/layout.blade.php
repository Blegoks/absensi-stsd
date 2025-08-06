<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Dashboard Anggota</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">
<div class="flex h-screen">
<!-- Sidebar -->
<aside class="w-64 bg-blue-800 text-white flex flex-col">
    <div class="p-4 flex items-center justify-center border-b border-blue-700">
        <span class="font-bold text-xl">ANGGOTA</span>
    </div>
    <nav class="flex-1 p-4 space-y-2 text-sm">
        <a href="{{ route('anggota.dashboard') }}"
        class="flex items-center px-4 py-2 rounded {{ request()->is('anggota/dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
        <span class="material-icons mr-2">dashboard</span> Dashboard
    </a>

        <a href="{{ route('anggota.scan') }}"
            class="flex items-center px-4 py-2 rounded {{ request()->is('anggota/scan') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <span class="material-icons mr-2">face</span> Scan Wajah
        </a>

        {{-- ✅ Link tambahan daftar wajah --}}
        <a href="{{ route('anggota.formWajah') }}"
            class="flex items-center px-4 py-2 rounded {{ request()->is('anggota/daftar_wajah') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <span class="material-icons mr-2">person_add</span> Daftar Wajah
        </a>
        
         <p class="text-gray-400 mt-4 mb-2 uppercase text-xs">Saya</p>
    </a>
    <a href="{{ route('anggota.profile.show') }}" class="flex items-center px-4 py-2 hover:bg-gray-700 rounded">
        <span class="material-icons mr-2">account_circle</span> Profil
    </a>

        <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 hover:bg-blue-700 rounded"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="material-icons mr-2">logout</span> Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </nav>
    <div class="p-4 text-xs text-gray-400 border-t border-blue-700">
        Copyright © 2025 <span class="text-blue-400">ST Satya Dharma</span><br>Created by <span class="text-blue-400">Wahyu</span>
    </div>
</aside>


    <!-- Main content -->
    <main class="flex-1 p-6 overflow-auto">
        <header class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold">@yield('title', 'Dashboard')</h1>
                @if(Auth::user()->anggota && Auth::user()->anggota->tempekan)
                    <div class="text-sm text-gray-500">
                        Tempekan: <span class="text-blue-600 font-semibold">{{ Auth::user()->anggota->tempekan->nama }}</span>
                    </div>
                @endif
            </div>
            <div class="flex items-center space-x-4">
                <span class="material-icons">person</span>
                <span class="font-semibold">{{ Auth::user()->name }}</span>
                <img src="{{ 'https://www.gravatar.com/avatar/' . md5(strtolower(trim(Auth::user()->email))) . '?d=mp' }}"
                     class="w-8 h-8 rounded-full" alt="User" />
            </div>
        </header>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded shadow">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded shadow">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</div>

<noscript>
    <style>
        #logout-form {
            display: block !important;
        }
    </style>
</noscript>
</body>
</html>
