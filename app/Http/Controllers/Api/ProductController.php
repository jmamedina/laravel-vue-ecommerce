<?php

namespace App\Http\Controllers\Api;

use App\Models\Api\Product;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search', false);
        $perPage = request('per_page', 10);
        $sortField = request('sort_field', 'id');
        $sortDirection = request('sort_direction','asc');

        $query = Product::query();
        $query->orderBy($sortField, $sortDirection);
        if($search){
            $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
        }

       return ProductListResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = $request->user()->id;
        $data['updated_by'] = $request->user()->id;

        $image = $data['image'] ?? null;
        if($image){
            $relativePath = $this->saveImage($image);
            $data['image'] = URL::to(Storage::url($relativePath));
            $data['image_mime'] = $image->getCLientMimeType();
            $data['image_suze'] = $image->getSize();
        }

        $product = Product::create($data);

        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show($product)
    {
        $post = Product::find($product);
        return $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $product)
    {
        $data = $request->validated();
        $data['updated_by'] = $request->user()->id;

        $post = Product::find($request->id);

        $image = $data['image'] ?? null;
        if($image){

             //if there is an old image
             if ($post->image){
                $parts =  Explode('/', $post->image);
                Storage::deleteDirectory('/images/' .  $parts[5]);
                Storage::deleteDirectory('/public/' . 'images/' .  $parts[5]);
            }

            $relativePath = $this->saveImage($image);
            $post->image = URL::to(Storage::url($relativePath));
            $post->image_mime = $image->getCLientMimeType();
            $post->image_suze = $image->getSize();
        }

        if($post)
        {
            $post->title = $request->title;
            $post->description = $request->description;
            $post->price = $request->price;
            return $post->update();
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($product)
    {
        $post = Product::find($product);
        if($product){
            $post->delete();
            return response()->json([
                'status' => 200,
                'message' => 'deleted'
            ]);
        }else{
            return response()->json([
                'status' => 404,
                'message' => 'yes'
            ]);
        }
    }

    private function saveImage(UploadedFile $image)
    {
        $path = 'images/' . Str::random();
        if(!Storage::exists($path)){
            Storage::makeDirectory($path, 0755, true);
        }
        if (!Storage::putFileAs('public/' . $path, $image, $image->getClientOriginalName())) {
            throw new \Exception("Unable to save file \"{$image->getClientOriginalName()}\"");
        }

        return $path . '/' . $image->getClientOriginalName();
    }
}
