<x-app-layout>
    
    <!-- cart item page -->
    <main class="p-5">
        <div class="containter lg:w-2/3 xl:w-2/3 mx-auto">
            <h1 class="text-3xl font-bold mb-6">My Cart Items</h1>
            <div x-data="{
                cartItems: {{
                    json_encode(
                        $products->map(fn($product)=> [
                            'id' => $product->id,
                            'slug' => $product->slug,
                            'image' => $product->image,
                            'title' => $product->title,
                            'price' => $product->price,
                            'quantity' => $cartItems[$product->id]['quantity'],
                            'href' => route('product.view', $product->slug),
                            'removeUrl' => route('cart.remove', $product),
                            'updateQuantityUrl' => route('cart.update-quantity', $product)
                            ])
                        )
                    }},
                    get cartTotal() {
                        return this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toFixed(2)
                    },
              }" class="bg-white p-4 rounded-lg shadow">

                <div>
                    <template x-for="product of cartItems" :key="product.id">
                        <div>
                            <div x-data="productItem(product)"
                                class="w-full flex flex-col sm:flex-row items-center gap-4">
                                <a href="/src/product.html"
                                    class="w-36 h-32 flex items-center justify-center overflow-hidden">
                                    <img :src="product.image" class="object-cover" alt="" />
                                </a>
                                <div class="flex-1 flex flex-col justify-between">
                                    <div class="flex justify-between mb-3">
                                        <h3 x-text="product.title"></h3>
                                        <span class="text-lg font-semibold">
                                            $<span x-text="product.price"></span>
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            Qty:
                                            <input type="number" min="1" x-model="product.quantity"
                                            @change="changeQuantity()"
                                                class="ml-3 py-1 border-gray-200 focus:border-purple-600 focus:ring-purple-600 w-16" />
                                        </div>
                                        <a @click.prevent="removeItemFromCart()" href="#"
                                            class="text-purple-600 hover:text-purple-500">Remove</a>
                                    </div>
                                </div>
                            </div>
                            <!--/ Product Item -->
                            <hr class="my-5" />
                        </div>
                    </template>
                </div>

                <!-- cart item-->
                <div class="border-t border-gray-300 mt-3 mt-5 pt-5">
                    <div class="flex justify-between">
                        <span class="font-bold"> Subtotal </span>
                        <span x-text="cartTotal"> </span>
                    </div>
                    <p class="text-gray-500 mb-6">
                        Shipping and tax will be applied on checkout
                    </p>
                </div>
                <form action="{{ route('cart.checkout') }}" method="POST">
                    @csrf
                    <button
                        class="transition-colors shadow-md items-center text-white bg-purple-500 py-2 px-3 rounded hover:bg-purple-600 active:bg-purple-700 w-full">
                        Checkout
                    </button>
                </form>
            </div>
        </div>
    </main>
    <!-- cart item page/-->

</x-app-layout>