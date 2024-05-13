<?php

/**
 * This file contains the LoginRequest class which handles the validation and authentication of login requests.
 * このファイルには、ログインリクエストの検証と認証を担当するLoginRequestクラスが含まれています。
 */

namespace App\Http\Requests\Auth;

use App\Enums\CustomerStatus;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * LoginRequest class handles the validation and authentication of login requests.
 * LoginRequestクラスは、ログインリクエストの検証と認証を担当します。
 */
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * ユーザーがこのリクエストを行うことを許可されているかどうかを決定します。
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * リクエストに適用される検証ルールを取得します。
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'], // Email field is required and must be a valid email address
            'password' => ['required', 'string'], // Password field is required
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     * リクエストの資格情報を認証しようとします。
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate()
    {
        $this->ensureIsNotRateLimited(); // Check if request is rate limited

        if (!Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey()); // Increment login attempt count

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'), // Authentication failed message
            ]);
        }

        $user = $this->user(); // Get authenticated user
        $customer = $user->customer; // Get associated customer
        if ($customer->status !== CustomerStatus::Active->value) {
            // Logout user
            Auth::guard('web')->logout();
            // Invalidate session
            $this->session()->invalidate();
            // Regenerate CSRF token
            $this->session()->regenerateToken();

            // Account disabled message
            throw ValidationException::withMessages([
                'email' => 'Your account has been disabled',
            ]);
        }

        // Clear rate limiting for successful login
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     * ログインリクエストがレート制限されていないことを確認します。
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        // Get remaining seconds until next attempt
        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds, // Remaining seconds
                'minutes' => ceil($seconds / 60), // Remaining minutes
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     * リクエストのレート制限スロットルキーを取得します。
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::lower($this->input('email')) . '|' . $this->ip();
    }
}
