<x-app-layout>
    <main class="p-5">
        <div class="grid gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 p-5">

        @foreach($products as $product)
            <!-- Product item -->
            <div x-data="productItem({{ json_encode([
                    'id' => $product->id,
                    'slug' => $product->slug,
                    'image' => $product->image,
                    'title' => $product->title,
                    'price' => $product->price,
                    'addToCartUrl' => route('cart.add', $product)
                ])
              }})" 
              class="overflow-hidden bg-white rounded-md shadow transition-colors border-gray-300 hover:border ">
              <div class="flex items-center justify-center aspect-w-3 aspect-h-2  ">
                <a href="{{ route('product.view', $product->slug) }}" class="aspect-w-3 aspect-h-2 block overflow-hidden">
                        <img class="rounded-t-md rounded-lg sm:h-[300px] transition hover:scale-105 hover:rotate-1" src="{{ $product->image }}" alt="">
                    </a>
                </div>

                <div class="p-3">
                    <h3>
                        <a href="{{ route('product.view', $product->slug) }}" class="hover:text-gray-700 font-semibold">
                            {{ $product->title }}
                        </a>
                    </h3>
                    <p class="text-xl font-bold"> $ {{ $product->price }} </p>
                    <div class="flex justify-between mt-3">
                        <button class="btn-primary" @click="addToCart()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            <!-- / Product item end-->
            @endforeach
        </div>
    </main>

    {{ $products->links() }}

</x-app-layout>