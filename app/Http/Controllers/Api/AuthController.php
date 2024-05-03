<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Controller for handling authentication-related API endpoints
// 認証に関連するAPIエンドポイントを処理するためのコントローラー
class AuthController extends Controller
{
    // Authenticate user and generate token
    // ユーザーを認証し、トークンを生成する
    public function login(Request $request)
    {
        // Validate request credentials
        // リクエストの資格情報を検証する
        $credentials = $request->validate([
            'email'=> ['required', 'email'],
            'password' => 'required',
            'remember' => 'boolean'
        ]);
        
        // Check if remember me option is selected
        // remember meオプションが選択されているかどうかを確認する
        $remember = $credentials['remember'] ?? false;
        unset($credentials['remember']);
        
        // Attempt to authenticate user
        // ユーザーを認証する
        if (!Auth::attempt($credentials, $remember)) {
            return response([
                'message' => 'Email or password is incorrect'
            ], 422);
        }

        // Retrieve authenticated user
        // 認証されたユーザーを取得する
        $user = Auth::user();
        
        // Check if user is admin
        // ユーザーが管理者かどうかを確認する
        if (!$user->is_admin) {
            Auth::logout();
            return response([
                'message' => 'You don\'t have permission to authenticate as admin'
            ], 403);
        }
        
        // Check if user's email is verified
        // ユーザーの電子メールが確認されているかどうかを確認する
        if (!$user->email_verified_at) {
            Auth::logout();
            return response([
                'message' => 'Your email address is not verified'
            ], 403);
        }
        
        // Generate token for user
        // ユーザーのトークンを生成する
        $token = $user->createToken('main')->plainTextToken;
        
        // Return response with user details and token
        // ユーザーの詳細とトークンを含むレスポンスを返す
        return response([
            'user' => new UserResource($user),
            'token' => $token
        ]);
    }

    // Logout user and revoke token
    // ユーザーをログアウトし、トークンを無効にする
    public function logout()
    {
        // Retrieve authenticated user and revoke token
        // 認証されたユーザーを取得し、トークンを無効にする
        $user = Auth::user();
        $user->currentAccessToken()->delete();

        // Return response
        // レスポンスを返す
        return response('', 204);
    }

    // Get authenticated user details
    // 認証されたユーザーの詳細を取得する
    public function getUser(Request $request)
    {
        // Return authenticated user details
        // 認証されたユーザーの詳細を返す
        return new UserResource($request->user());
    }
}
