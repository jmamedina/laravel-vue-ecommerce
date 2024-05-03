<?php

namespace App\Http\Controllers\Api;

use App\Enums\AddressType;
use App\Enums\CustomerStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CountryResource;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

// Controller for managing customer-related API endpoints
// 顧客関連のAPIエンドポイントを管理するためのコントローラー
class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     * リソースの一覧を表示します。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve customers based on search parameters and pagination
        // 検索パラメーターとページネーションに基づいて顧客を取得します
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        $query = Customer::query()
            ->with('user')
            ->orderBy("customers.$sortField", $sortDirection);
        if ($search) {
            $query
                ->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%")
                ->join('users', 'customers.user_id', '=', 'users.id')
                ->orWhere('users.email', 'like', "%{$search}%")
                ->orWhere('customers.phone', 'like', "%{$search}%");
        }

        $paginator = $query->paginate($perPage);

        // Return a collection of customer resources
        // 顧客リソースのコレクションを返します
        return CustomerListResource::collection($paginator);
    }

    /**
     * Display the specified resource.
     * 指定されたリソースを表示します。
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        // Return the customer resource
        // 顧客リソースを返します
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     * ストレージ内の指定されたリソースを更新します。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Customer     $customer
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, Customer $customer)
    {
        // Validate and update customer data including addresses
        // アドレスを含む顧客データを検証して更新します
        $customerData = $request->validated();
        $customerData['updated_by'] = $request->user()->id;
        $customerData['status'] = $customerData['status'] ? CustomerStatus::Active->value : CustomerStatus::Disabled->value;
        $shippingData = $customerData['shippingAddress'];
        $billingData = $customerData['billingAddress'];

        DB::beginTransaction();
        try {
            $customer->update($customerData);

            if ($customer->shippingAddress) {
                $customer->shippingAddress->update($shippingData);
            } else {
                $shippingData['customer_id'] = $customer->user_id;
                $shippingData['type'] = AddressType::Shipping->value;
                CustomerAddress::create($shippingData);
            }

            if ($customer->billingAddress) {
                $customer->billingAddress->update($billingData);
            } else {
                $billingData['customer_id'] = $customer->user_id;
                $billingData['type'] = AddressType::Billing->value;
                CustomerAddress::create($billingData);
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::critical(__METHOD__ . ' method does not work. '. $e->getMessage());
            throw $e;
        }

        DB::commit();

        // Return the updated customer resource
        // 更新された顧客リソースを返します
        return new CustomerResource($customer);
    }

    /**
     * Remove the specified resource from storage.
     * ストレージから指定されたリソースを削除します。
     *
     * @param \App\Models\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        // Delete the specified customer
        // 指定された顧客を削除します
        $customer->delete();

        // Return a no content response
        // 内容のないレスポンスを返します
        return response()->noContent();
    }

    /**
     * Get countries.
     * 国を取得します。
     *
     * @return \Illuminate\Http\Response
     */
    public function countries()
    {
        // Return a collection of country resources
        // 国のリソースのコレクションを返します
        return CountryResource::collection(Country::query()->orderBy('name', 'asc')->get());
    }
}
