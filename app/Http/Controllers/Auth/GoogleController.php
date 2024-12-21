<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AllowedEmail;
use Laravel\Socialite\Facades\Socialite;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // デバッグログを追加
            Log::info('Google User Data:', [
                'id' => $googleUser->getId(), // メソッドを使用
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName()
            ]);
            
            // メールアドレスが許可リストにあるか確認
            if (!AllowedEmail::isAllowed($googleUser->getEmail())) {
                return redirect()->route('login')
                    ->with('error', 'このメールアドレスではログインできません。');
            }

            // ユーザーが存在するか確認し、なければ作成
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if (!$user) {
                $userData = [
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => bcrypt(Str::random(16))
                ];
                
                // デバッグログを追加
                Log::info('Creating new user:', $userData);
                
                $user = User::create($userData);
            }

            Auth::login($user);
            return redirect()->intended('/');

        } catch (Exception $e) {
            // エラーログを追加
            Log::error('Google Authentication Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', '認証中にエラーが発生しました。');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
