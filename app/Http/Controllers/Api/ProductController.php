<?php

/**
 * ProductController
 * 商品コントローラー
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Api\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Url;
use Illuminate\Support\Str;

class ProductController extends Controller
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
        $sortField = request('sort_field', 'created_at');
        $sortDirection = request('sort_direction', 'desc');

        // Query products based on search and sorting parameters
        // 検索およびソートのパラメータに基づいて商品をクエリする
        $query = Product::query()
            ->where('title', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginate($perPage);

        return ProductListResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     * 新しく作成されたリソースを保存する。
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        // Validate the incoming request
        // 受信したリクエストを検証する
        $data = $request->validated();

        // Set the created_by and updated_by fields with the user's ID
        // created_by と updated_by フィールドをユーザーの ID で設定する
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        // Extract images, image positions, and categories from the data
        // データから画像、画像の位置、カテゴリーを取得する
        $images = $data['images'] ?? [];
        $imagePositions = $data['image_positions'] ?? [];
        $categories = $data['categories'] ?? [];

        // Create a new product instance
        // 新しい商品インスタンスを作成する
        $product = Product::create($data);

        // Save categories associated with the product
        // 商品に関連付けられたカテゴリーを保存する
        $this->saveCategories($categories, $product);

        // Save images associated with the product
        // 商品に関連付けられた画像を保存する
        $this->saveImages($images, $imagePositions, $product);

        // Return the newly created product as a resource
        // 新しく作成された商品をリソースとして返す
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     * 指定されたリソースを表示する。
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        // Return the product as a resource
        // 商品をリソースとして返す
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     * ストレージ内の指定されたリソースを更新する。
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product      $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        // Validate the incoming request
        // 受信したリクエストを検証する
        $data = $request->validated();

        // Set the updated_by field with the user's ID
        // updated_by フィールドをユーザーの ID で設定する
        $data['updated_by'] = $request->user()->id;

        // Extract images, deleted images, image positions, and categories from the data
        // データから画像、削除された画像、画像の位置、カテゴリーを取得する
        $images = $data['images'] ?? [];
        $deletedImages = $data['deleted_images'] ?? [];
        $imagePositions = $data['image_positions'] ?? [];
        $categories = $data['categories'] ?? [];

        // Save categories associated with the product
        // 商品に関連付けられたカテゴリーを保存する
        $this->saveCategories($categories, $product);

        // Save images associated with the product
        // 商品に関連付けられた画像を保存する
        $this->saveImages($images, $imagePositions, $product);

        // Delete images that are marked for deletion
        // 削除のためにマークされた画像を削除する
        if (count($deletedImages) > 0) {
            $this->deleteImages($deletedImages, $product);
        }

        // Update the product with the new data
        // 新しいデータで商品を更新する
        $product->update($data);

        // Return the updated product as a resource
        // 更新された商品をリソースとして返す
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     * ストレージから指定されたリソースを削除する。
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Delete the specified product
        // 指定された商品を削除する
        $product->delete();

        // Return a response with no content (204 - No Content)
        // コンテンツのないレスポンスを返す（204 - No Content）
        return response()->noContent();
    }

    // Helper function to save categories associated with a product
    // 商品に関連付けられたカテゴリーを保存するためのヘルパー関数
    private function saveCategories($categoryIds, Product $product)
    {
        // Delete existing product-category associations
        // 既存の商品カテゴリーの関連付けを削除する
        ProductCategory::where('product_id', $product->id)->delete();

        // Create an array of data to insert into the product-category table
        // 商品カテゴリーテーブルに挿入するデータの配列を作成する
        $data = array_map(fn($id) => (['category_id' => $id, 'product_id' => $product->id]), $categoryIds);

        // Insert the data into the product-category table
        // データを商品カテゴリーテーブルに挿入する
        ProductCategory::insert($data);
    }

    // Helper function to save images associated with a product
    // 商品に関連付けられた画像を保存するためのヘルパー関数
    private function saveImages($images, $positions, Product $product)
    {
        // Update image positions
        // 画像の位置を更新する
        foreach ($positions as $id => $position) {
            ProductImage::query()
                ->where('id', $id)
                ->update(['position' => $position]);
        }

        // Save new images
        // 新しい画像を保存する
        foreach ($images as $id => $image) {
            $path = 'images/' . Str::random();
            if (!Storage::exists($path)) {
                Storage::makeDirectory($path, 0755, true);
            }
            $name = Str::random().'.'.$image->getClientOriginalExtension();
            if (!Storage::putFileAs('public/' . $path, $image, $name)) {
                throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
            }
            $relativePath = $path . '/' . $name;

            // Create a new ProductImage instance and save it
            // 新しい ProductImage インスタンスを作成し、保存する
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $relativePath,
                'url' => Url::to(Storage::url($relativePath)),
                'mime' => $image->getClientMimeType(),
                'size' => $image->getSize(),
                'position' => $positions[$id] ?? $id + 1
            ]);
        }
    }

    // Helper function to delete images associated with a product
    // 商品に関連付けられた画像を削除するためのヘルパー関数
    private function deleteImages($imageIds, Product $product)
    {
        // Query images to delete
        // 削除する画像をクエリする
        $images = ProductImage::query()
            ->where('product_id', $product->id)
            ->whereIn('id', $imageIds)
            ->get();

        // Delete each image
        // それぞれの画像を削除する
        foreach ($images as $image) {
            // If there is an old image, delete it
            // 古い画像があれば、削除する
            if ($image->path) {
                Storage::deleteDirectory('/public/' . dirname($image->path));
            }
            $image->delete();
        }
    }
}
