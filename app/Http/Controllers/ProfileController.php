<?php

/**
 * Controller responsible for handling profile-related operations.
 * プロフィール関連の操作を処理するコントローラーです。
 */

namespace App\Http\Controllers;

use App\Enums\AddressType;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Country;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class ProfileController extends Controller
{
    // View user profile
    // ユーザープロフィールを表示する
    public function view(Request $request)
    {
        /** @var \App\Models\User $user */
        // Get the authenticated user
        // 認証されたユーザーを取得する
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        // Get the customer associated with the user
        // ユーザーに関連付けられた顧客情報を取得する
        $customer = $user->customer;
        // Get shipping and billing addresses or create new ones
        // 送付先と請求先の住所を取得するか、新しいものを作成する
        $shippingAddress = $customer->shippingAddress ?: new CustomerAddress(['type' => AddressType::Shipping]);
        $billingAddress = $customer->billingAddress ?: new CustomerAddress(['type' => AddressType::Billing]);
        // Get countries list for address forms
        // 住所フォーム用の国リストを取得する
        $countries = Country::query()->orderBy('name')->get();

        return view('profile.view', compact('customer', 'user', 'shippingAddress', 'billingAddress', 'countries'));
    }

    // Update user profile
    // ユーザープロフィールを更新する
    public function store(ProfileRequest $request)
    {
        // Validate request data
        // リクエストデータを検証する
        $customerData = $request->validated();
        $shippingData = $customerData['shipping'];
        $billingData = $customerData['billing'];

        /** @var \App\Models\User $user */
        // Get the authenticated user
        // 認証されたユーザーを取得する
        $user = $request->user();
        /** @var \App\Models\Customer $customer */
        // Get the customer associated with the user
        // ユーザーに関連付けられた顧客情報を取得する
        $customer = $user->customer;

        // Begin database transaction
        // データベーストランザクションを開始する
        DB::beginTransaction();
        try {
            // Update customer information
            // 顧客情報を更新する
            $customer->update($customerData);

            // Update or create shipping address
            // 送付先住所を更新または作成する
            if ($customer->shippingAddress) {
                $customer->shippingAddress->update($shippingData);
            } else {
                $shippingData['customer_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                CustomerAddress::create($shippingData);
            }

            // Update or create billing address
            // 請求先住所を更新または作成する
            if ($customer->billingAddress) {
                $customer->billingAddress->update($billingData);
            } else {
                $billingData['customer_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                CustomerAddress::create($billingData);
            }
        } catch (\Exception $e) {
            // Rollback transaction in case of error
            // エラーが発生した場合はトランザクションをロールバックする
            DB::rollBack();

            // Log critical error
            // 重大なエラーをログに記録する
            Log::critical(__METHOD__ . ' method does not work. '. $e->getMessage());
            throw $e;
        }

        // Commit transaction
        // トランザクションをコミットする
        DB::commit();

        // Flash success message
        // 成功メッセージをフラッシュする
        $request->session()->flash('flash_message', 'Profile was successfully updated.');

        return redirect()->route('profile');
    }

    // Update user password
    // ユーザーパスワードを更新する
    public function passwordUpdate(PasswordUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        // Get the authenticated user
        // 認証されたユーザーを取得する
        $user = $request->user();

        // Validate request data
        // リクエストデータを検証する
        $passwordData = $request->validated();

        // Update user password
        // ユーザーパスワードを更新する
        $user->password = Hash::make($passwordData['new_password']);
        $user->save();

        // Flash success message
        // 成功メッセージをフラッシュする
        $request->session()->flash('flash_message', 'Your password was successfully updated.');

        return redirect()->route('profile');
    }
}
