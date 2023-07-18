<x-app-layout>
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 py-5 px-5">
        <div class="lg:col-span-3">
            <div x-data="{
                    images: ['{{ $product->image}}', 'img/Airpods2.jpg', 'img/Airpods3.jpg', 'img/Airpods4.jpg', 'img/Airpods5.jpg', 'img/Airpods6.jpg'],
                    activeImage: null,
                    prev() {
                        let index = this.images.indexOf(this.activeImage);
                        if (index === 0) 
                            index = this.images.length;
                        this.activeImage = this.images[index - 1];
                    },
                    next() {
                        let index = this.images.indexOf(this.activeImage);
                        if (index === this.images.length - 1) 
                            index = -1;
                        this.activeImage = this.images[index + 1];
                    },
                    init() {
                        this.activeImage = this.images.length > 0 ? this.images[0] : null
                    }
                  }">
                <div class="relative">
                    <template x-for="image in images">
                        <div x-show="activeImage === image" class="aspect-w-3 aspect-h-2">
                            <img :src="image" alt="" class="w-auto mx-auto" />
                        </div>
                    </template>
                    <a @click.prevent="prev" class="cursor-pointer bg-black/30 text-white absolute left-0 top-1/2 -translate-y-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <a @click.prevent="next" class="cursor-pointer bg-black/30 text-white absolute right-0 top-1/2 -translate-y-1/2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
                <div class="flex">
                    <template x-for="image in images">
                        <a @click.prevent="activeImage = image" class="cursor-pointer w-[80px] border border-gray-300 hover:border-purple-500 flex items-center justify-center" :class="{'border-purple-600': activeImage === image}">
                            <img :src="image" alt="" class="w-auto max-auto max-h-full" />
                        </a>
                    </template>
                </div>
            </div>
        </div>
        <div class="col-span-2">
            <h1 class="text-lg font-semibold">
                {{ $product->title }}
            </h1>
            <p class="text-xl font-bold mb-3">
                {{ $product->price }}
            </p>

            <div class="flex justify-between items-center mb-3">
                <label class="mr-4" for="quantity">
                    Quantity
                </label>
                <input class=" w-24 focus:border-purple-600 border-2 border-gray-300 rounded focus:ring-purple-600 py-2 px-3" value="1" name="quantity" x-ref="quantityEl" type="number" />
            </div>

            <button @click="addToCart(id, $refs.quantityEl.value)" class="btn-primary w-full justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                </svg>
                Add to cart
            </button>

            <hr class="my-5 border-white">
            <div class="mb-6" x-data="{ expanded: false }">
                <div 
                    x-show="expanded" 
                    x-collapse.min.100px 
                    class="description-editor">
                   {{ $product->description }}
                
            </div>
                <p class="text-right">
                    <a 
                        @click="expanded = !expanded" 
                        href="javascript:void(0)" 
                        class="text-purple-500 hover:text-purple-700" 
                        x-text="expanded ? 'Read Less' : 'Read More'">
                    </a>
                </p>
        </div>
    </div>
</x-app-layout>