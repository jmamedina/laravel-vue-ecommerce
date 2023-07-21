<x-app-layout>
<main class="p-5 mt-5">
    <div class="containter mx-auto lg:w-2/3 xl:w-2/3 mx-auto">
            <h1 class="text-3xl font-bold mb-6">My Orders</h1>
            <div class="bg-white rounded-lg p-4 shadow-md overflow-x-auto">
                <table class="table-auto w-full">
                    <thead>
                        <tr class="border-b-2">
                            <th class="text-left p-2">Order #</th>
                            <th class="text-left p-2">Date</th>
                            <th class="text-left p-2">Status</th>
                            <th class="text-left p-2">Subtotal</th>
                            <th class="text-left p-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr class="border-b">
                            <td class="py-1 px-2">
                                <a href="{{ route('order.view', $order) }}" class="text-purple-600 hover:text-purple-500">
                                    #{{ $order->id }}
                                </a>
                            </td>
                            <td class="py-1 px-2 whitespace-nowrap">
                                {{ $order->created_at }}
                            </td>
                            <td class="py-1 px-2">
                                <small class="text-white py-1 px-2 rounded
                                {{ $order->isPaid() ? 'bg-emerald-500' : 'bg-gray-400' }}
                                "> {{ $order->status }} 
                            </small>
                            </td>
                            <td>
                                ${{ $order->total_price }}
                            </td>
                            <td class="flex gap-3">
                                <button class="flex items-center py-1 px-2 btn-primary  whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                      </svg>
                                      View invoice 
                                </button>
                                  
                                @if (!$order->isPaid())
                                <form action="{{ route('cart.checkout-order', $order) }}" method="post">
                                    @csrf
                                    <button class="flex items-center py-1 px-2 btn-emerald  whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                      </svg>
                                      Pay 
                                    </button>
                                </form>
                                @endif                            
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</main>
</x-app-layout>