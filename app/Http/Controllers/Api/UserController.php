<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Api\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * リソースの一覧を表示する。
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve parameters from the request
        // リクエストからパラメータを取得する
        $perPage = request('per_page', 10);
        $search = request('search', '');
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');

        // Query users based on search and sorting parameters
        // 検索およびソートのパラメータに基づいてユーザーをクエリする
        $query = User::query()
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        // Return the user resources as a collection
        // ユーザーリソースをコレクションとして返す
        return UserResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     * 新しく作成されたリソースを保存する。
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        // Validate the incoming request
        // 受信したリクエストを検証する
        $data = $request->validated();

        // Set default values for some fields
        // 一部のフィールドのデフォルト値を設定する
        $data['is_admin'] = true;
        $data['email_verified_at'] = now();
        $data['password'] = Hash::make($data['password']);

        // Set the created_by and updated_by fields with the user's ID
        // created_by と updated_by フィールドをユーザーの ID で設定する
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        // Create a new user instance
        // 新しいユーザーインスタンスを作成する
        $user = User::create($data);

        // Return the newly created user as a resource
        // 新しく作成されたユーザーをリソースとして返す
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     * ストレージ内の指定されたリソースを更新する。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // Validate the incoming request
        // 受信したリクエストを検証する
        $data = $request->validated();

        // If password is provided, hash it
        // パスワードが提供された場合は、ハッシュ化する
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Set the updated_by field with the user's ID
        // updated_by フィールドをユーザーの ID で設定する
        $data['updated_by'] = $request->user()->id;

        // Update the user with the new data
        // 新しいデータでユーザーを更新する
        $user->update($data);

        // Return the updated user as a resource
        // 更新されたユーザーをリソースとして返す
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     * ストレージから指定されたリソースを削除する。
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Delete the specified user
        // 指定されたユーザーを削除する
        $user->delete();

        // Return a response with no content (204 - No Content)
        // コンテンツのないレスポンスを返す（204 - No Content）
        return response()->noContent();
    }
}
