<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryTreeResource;
use App\Models\Category;

// Controller for handling category-related API endpoints
// カテゴリー関連のAPIエンドポイントを処理するためのコントローラー
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * リソースの一覧を表示します。
     */
    public function index()
    {
        // Retrieve categories and order them based on request parameters
        // カテゴリーを取得し、リクエストパラメータに基づいて順序付けします
        $sortField = request('sort_field', 'updated_at');
        $sortDirection = request('sort_direction', 'desc');
        $categories = Category::query()
            ->orderBy($sortField, $sortDirection)
            ->latest()
            ->get();

        // Return a collection of category resources
        // カテゴリーリソースのコレクションを返します
        return CategoryResource::collection($categories);
    }

    /**
     * Get categories as tree structure.
     * カテゴリーをツリー構造として取得します。
     */
    public function getAsTree()
    {
        // Retrieve active categories as tree structure
        // アクティブなカテゴリーをツリー構造として取得します
        return Category::getActiveAsTree(CategoryTreeResource::class);
    }

    /**
     * Store a newly created resource in storage.
     * 新しく作成されたリソースを保存します。
     */
    public function store(StoreCategoryRequest $request)
    {
        // Validate and store the new category data
        // 新しいカテゴリーデータを検証して保存します
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;
        $category = Category::create($data);

        // Return the created category resource
        // 作成されたカテゴリーリソースを返します
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     * ストレージ内の指定されたリソースを更新します。
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // Validate and update the category data
        // カテゴリーデータを検証して更新します
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;
        $category->update($data);

        // Return the updated category resource
        // 更新されたカテゴリーリソースを返します
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     * ストレージから指定されたリソースを削除します。
     */
    public function destroy(Category $category)
    {
        // Delete the specified category
        // 指定されたカテゴリーを削除します
        $category->delete();

        // Return a no content response
        // 内容のないレスポンスを返します
        return response()->noContent();
    }
}
