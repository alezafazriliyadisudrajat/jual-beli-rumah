<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    # Menangani proses registrasi akun baru.
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:150',
                'email' => 'required|string|email|max:150|unique:users',
                'phone' => 'required|string|max:20',
                'password' => 'required|string|min:8|confirmed',
                'role_id' => 'required|in:3,4,5',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role_id' => $request->role_id,
                'is_verified' => in_array($request->role_id, [1, 2, 5]), # Admin dan Pembeli otomatis terverifikasi, Pemilik/Agen memerlukan verifikasi KTP
            ]);

            AuditLogService::log($user->id, 'REGISTER', "Pengguna baru mendaftar dengan email: {$user->email}");

            Auth::login($user);

            if ($request->wantsJson()) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => 'Registrasi berhasil',
                    'data' => ['user' => $user, 'token' => $token]
                ]);
            }

            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil. Selamat datang!');
        } catch (\Exception $e) {
            Log::error("Error in AuthController@register: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan sistem saat registrasi. Silakan coba lagi.')->withInput();
        }
    }

    # Menangani proses login pengguna.
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Email atau password salah.'], 401);
                }
                return back()->with('error', 'Email atau password salah.')->withInput();
            }

            $user = Auth::user();
            AuditLogService::log($user->id, 'LOGIN', "Pengguna masuk ke sistem.");

            if ($request->wantsJson()) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'data' => ['user' => $user, 'token' => $token]
                ]);
            }

            // Mencegah redirect ke endpoint AJAX chat jika session sempat terkontaminasi saat logout/expired
            $intended = session()->get('url.intended');
            if ($intended && (str_contains($intended, '/chat') || str_contains($intended, '/messages'))) {
                session()->forget('url.intended');
            }

            return redirect()->intended('/dashboard')->with('success', 'Login berhasil!');
        } catch (\Exception $e) {
            Log::error("Error in AuthController@login: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan sistem.'], 500);
            }
            return back()->with('error', 'Terjadi kesalahan sistem saat login. Silakan coba lagi.')->withInput();
        }
    }

    # Menangani proses logout pengguna.
    public function logout(Request $request)
    {
        try {
            $user = Auth::user();
            if ($user) {
                AuditLogService::log($user->id, 'LOGOUT', "Pengguna keluar dari sistem.");
                if ($request->wantsJson()) {
                    $user->tokens()->delete();
                }
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Logout berhasil.']);
            }

            return redirect('/')->with('success', 'Logout berhasil.');
        } catch (\Exception $e) {
            Log::error("Error in AuthController@logout: " . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat logout.'], 500);
            }
            return redirect('/')->with('error', 'Terjadi kesalahan saat logout.');
        }
    }

    # Mengambil data detail profil pengguna yang sedang login.
    public function me(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Detail profil berhasil diambil',
                'data' => Auth::user()
            ]);
        } catch (\Exception $e) {
            Log::error("Error in AuthController@me: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan mengambil profil.'], 500);
        }
    }

    # Memperbarui profil pengguna & mengunggah dokumen KTP untuk verifikasi.
    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->route('login');
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:150',
                'phone' => 'required|string|max:20',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg,bmp|max:4096',
                'ktp_document' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,bmp,webp|max:5120', # Untuk verifikasi dokumen KTP pemilik/agen
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            }

            $updateData = [
                'name' => $request->name,
                'phone' => $request->phone,
            ];

            # Menangani unggahan atau penghapusan file Avatar
            if ($request->delete_avatar == '1') {
                $updateData['avatar'] = null;
            } elseif ($request->hasFile('avatar')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $updateData['avatar'] = '/storage/' . $avatarPath;
            }

            # Menangani unggahan KTP untuk verifikasi akun
            if ($request->hasFile('ktp_document')) {
                # Dalam aplikasi nyata, simpan file ke penyimpanan non-publik yang aman
                $ktpPath = $request->file('ktp_document')->store('ktp_documents', 'public');
                # Set status verifikasi ke pending (false) agar admin memoderasi dokumen
                $updateData['is_verified'] = false;
                AuditLogService::log($user->id, 'UPLOAD_KTP', "Mengunggah dokumen verifikasi KTP.");
            }

            $user->update($updateData);

            AuditLogService::log($user->id, 'UPDATE_PROFILE', "Memperbarui data profil.");

            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Error in AuthController@updateProfile: " . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui profil.');
        }
    }
}
