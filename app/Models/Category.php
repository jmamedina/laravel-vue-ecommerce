<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    // Fillable fields for mass assignment
    // 一括代入用のフィールド
    protected $fillable = ['name', 'slug', 'active', 'parent_id', 'created_by', 'updated_by'];

    // Define sluggable behavior
    // スラッグ付きの動作を定義する
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    // Relationship: Parent category
    // 関連性: 親カテゴリ
    public function parent()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship: Products belonging to this category
    // 関連性: このカテゴリに属する製品
    public function products()
    {
        return $this->belongsToMany(Product::class); // product_category
    }

    // Get active categories as a tree structure
    // アクティブなカテゴリをツリー構造として取得する
    public static function getActiveAsTree($resourceClassName = null)
    {
        $categories = Category::where('active', true)->orderBy('parent_id')->get();
        return self::buildCategoryTree($categories, null, $resourceClassName);
    }

    // Get all children categories by parent category
    // 親カテゴリによってすべての子カテゴリを取得する
    public static function getAllChildrenByParent(Category $category)
    {
        $categories = Category::where('active', true)->orderBy('parent_id')->get();
        $result[] = $category;
        self::getCategoriesArray($categories, $category->id, $result);

        return $result;
    }

    private static function buildCategoryTree($categories, $parentId = null, $resourceClassName = null)
    {
        $categoryTree = [];

        foreach ($categories as $category) {
            if ($category->parent_id === $parentId) {
                $children = self::buildCategoryTree($categories, $category->id, $resourceClassName);
                if ($children) {
                    $category->setAttribute('children', $children);
                }
                $categoryTree[] = $resourceClassName ? new $resourceClassName($category) : $category;
            }
        }

        return $categoryTree;
    }

    private static function getCategoriesArray($categories, $parentId, &$result)
    {
        foreach ($categories as $category) {
            if ($category->parent_id === $parentId) {
                $result[] = $category;
                self::getCategoriesArray($categories, $category->id, $result);
            }
        }
    }
}
