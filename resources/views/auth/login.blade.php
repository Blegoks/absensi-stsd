<x-guest-layout>
    <div class="mb-8 text-center">
        <svg class="mx-auto w-16 h-16 text-indigo-700 mb-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 48 48"><circle cx="24" cy="24" r="22" stroke="#6366f1" stroke-width="4" fill="#f1f5fe"/><path d="M16 24l6 6 10-10" stroke="#ef4444" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h2 class="text-2xl font-bold text-indigo-800 mb-1">Login Absensi STSD</h2>
        <p class="text-gray-500 text-sm">Silakan masuk untuk melanjutkan ke dashboard</p>
    </div>
    @if (session('status'))
        <div class="mb-4 text-green-600 text-sm font-medium text-center">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="space-y-6" autocomplete="on">
        @csrf
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" inputmode="email" class="block w-full px-4 py-3 border border-indigo-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg bg-gray-50 text-gray-800" />
            @error('email')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="block w-full px-4 py-3 border border-indigo-200 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-lg bg-gray-50 text-gray-800" />
            @error('password')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            @if (Route::has('password.request'))
                <a class="text-sm text-indigo-700 hover:underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>
        <div>
            <button type="submit" class="w-full bg-gradient-to-r from-indigo-800 to-red-500 text-white font-semibold px-6 py-3 rounded-lg shadow hover:from-red-500 hover:to-indigo-800 transition text-base">Masuk</button>
        </div>
    </form>
</x-guest-layout>
