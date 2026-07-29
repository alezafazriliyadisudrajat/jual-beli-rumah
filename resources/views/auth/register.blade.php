@extends('layouts.app')

@section('title', 'Daftar Akun - PropertiImpian')

@section('content')
<div class="max-w-md mx-auto my-12">
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-2xl shadow-xl overflow-hidden p-8">
        <div class="text-center mb-8">
            <h1 class="heading-font text-3xl font-bold bg-gradient-to-r from-slate-900 to-indigo-950 dark:from-white dark:to-indigo-200 bg-clip-text text-transparent">Daftar Akun Baru</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">Buat akun untuk mulai mencari atau memasarkan properti</p>
        </div>

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nama Lengkap</label>
                <div class="mt-1">
                    <input type="text" name="name" id="name" required value="{{ old('name') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                @error('name')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

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

            <!-- Telepon / WhatsApp -->
            <div>
                <label for="phone" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Nomor HP / WhatsApp</label>
                <div class="mt-1">
                    <input type="text" name="phone" id="phone" required placeholder="Contoh: 081234567890" value="{{ old('phone') }}"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                @error('phone')
                    <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            <!-- Peran / Role -->
            <div>
                <label for="role_id" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Peran Pengguna</label>
                <div class="mt-1">
                    <select name="role_id" id="role_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                        <option value="" disabled selected>Pilih peran Anda</option>
                        <option value="5" {{ old('role_id') == 5 ? 'selected' : '' }}>Pembeli / Penyewa</option>
                        <option value="4" {{ old('role_id') == 4 ? 'selected' : '' }}>Penjual / Pemilik Properti</option>
                        <option value="3" {{ old('role_id') == 3 ? 'selected' : '' }}>Agen Properti</option>
                    </select>
                </div>
                <p class="text-xs text-slate-400 mt-1">Catatan: Pemilik dan Agen membutuhkan verifikasi dokumen KTP setelah mendaftar.</p>
                @error('role_id')
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

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konfirmasi Kata Sandi</label>
                <div class="mt-1">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 dark:border-zinc-800 bg-slate-50 dark:bg-zinc-950 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/10 hover:shadow-indigo-600/20 active:scale-98 transition-all">
                Daftar Akun
            </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
            Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Masuk</a>
        </div>
    </div>
</div>
@endsection
