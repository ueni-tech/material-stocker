<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // APIリクエストの場合は401レスポンス
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => '認証が必要です。',
                ], 401);
            }

            // 現在のURLを保存してリダイレクト
            return redirect()->route('login')
                ->with('error', 'ログインが必要です。')
                ->with('redirect', $request->fullUrl());
        }

        // セッションの有効期限を延長
        if ($request->session()->has('auth')) {
            $request->session()->regenerate();
        }


        return $next($request);
    }
}
