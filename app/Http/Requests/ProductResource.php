<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class ProductResource extends FormRequest
{
    /** determine if the user is authorized to make this request  */

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image ?: null,
            'price' => $this->price,
            'published' => (bool)$this->published,
            'created_at' => (new \DateTime($this->created_at))->format('Y-m-d H:i:s'),
            'updated_at' => (new \DateTime($this->updated_at))->format('Y-m-d H:i:s')
        ];
    }
}