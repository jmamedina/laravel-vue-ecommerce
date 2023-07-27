<?php

namespace App\Http\Controllers\Api;
use App\Http\Resources\OrderListResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
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

        $query = Order::query();

        $query->orderBy($sortField, $sortDirection);
        if($search){
            $query->where('id', 'like', "%{$search}%")
            ->orderBy($sortField, $sortDirection)
            ->paginator($perPage);
        }

        return OrderListResource::collection($query->paginate($perPage));
    }

    public function view(Order $order)
    {
        $order->load('items.product');
        
        return new OrderResource($order);
    }
}
