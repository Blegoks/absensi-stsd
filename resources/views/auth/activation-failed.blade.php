<x-guest-layout>
    <div class="max-w-lg mx-auto mt-10 bg-white p-6 rounded shadow text-center">
        <h2 class="text-2xl font-bold text-red-600 mb-4">Link Aktivasi Tidak Valid</h2>
        <p class="mb-4">Token aktivasi mungkin sudah digunakan atau tidak ditemukan.</p>
        <a href="{{ route('login') }}" class="text-blue-600 underline font-semibold">Kembali ke Login</a>
    </div>
</x-guest-layout>
