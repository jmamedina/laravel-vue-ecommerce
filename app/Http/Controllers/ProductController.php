<?php

/**
 * Controller responsible for handling product-related operations.
 * 商品関連の操作を処理するコントローラーです。
 */

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Display a paginated list of all products
    // 全商品の一覧をページネーションして表示する
    public function index()
    {
        // Create a base query for products
        // 商品のためのベースクエリを作成する
        $query = Product::query();

        return $this->renderProducts($query);
    }

    // Display a paginated list of products by category
    // カテゴリー別の商品の一覧をページネーションして表示する
    public function byCategory(Category $category)
    {
        // Get all child categories of the specified category
        // 指定されたカテゴリーのすべての子カテゴリーを取得する
        $categories = Category::getAllChildrenByParent($category);

        // Create a query to fetch products by category
        // カテゴリー別に商品を取得するクエリを作成する
        $query = Product::query()
            ->select('products.*')
            ->join('product_categories AS pc', 'pc.product_id', 'products.id')
            ->whereIn('pc.category_id', array_map(fn($c) => $c->id, $categories));

        return $this->renderProducts($query);
    }

    // View a specific product
    // 特定の商品を表示する
    public function view(Product $product)
    {
        return view('product.view', ['product' => $product]);
    }

    // Render products based on the given query
    // 指定されたクエリに基づいて商品を表示する
    private function renderProducts(Builder $query)
    {
        // Get search query and sorting criteria from request
        // リクエストから検索クエリと並べ替え条件を取得する
        $search = \request()->get('search');
        $sort = \request()->get('sort', '-updated_at');

        // Apply sorting if specified
        // 指定された場合は並べ替えを適用する
        if ($sort) {
            $sortDirection = 'asc';
            if ($sort[0] === '-') {
                $sortDirection = 'desc';
            }
            $sortField = preg_replace('/^-?/', '', $sort);

            $query->orderBy($sortField, $sortDirection);
        }

        // Fetch products based on search query and pagination
        // 検索クエリとページネーションに基づいて商品を取得する
        $products = $query
            ->where('published', '=', 1)
            ->where(function ($query) use ($search) {
                /** @var $query \Illuminate\Database\Eloquent\Builder */
                $query->where('products.title', 'like', "%$search%")
                    ->orWhere('products.description', 'like', "%$search%");
            })
            ->paginate(5);

        return view('product.index', [
            'products' => $products
        ]);
    }
}
