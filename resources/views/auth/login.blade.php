@extends('layouts.app')

@section('title', 'Masuk - PropertiImpian')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="text-center mb-8">
            <h1 class="heading-font text-3xl font-bold bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-white dark:to-indigo-200 bg-clip-text text-transparent">Selamat Datang Kembali</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Masuk ke akun Anda untuk melanjutkan transaksi</p>
        </div>

        <form action="{{ route('login') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Alamat Email</label>
                <div class="mt-1">
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                @error('email')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Kata Sandi</label>
                <div class="mt-1">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                @error('password')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 active:scale-98 transition-all">
                Masuk
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
            Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Daftar sekarang</a>
        </div>
    </div>
</div>
@endsection
