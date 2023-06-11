<div>
    <div class="h-screen md:h-[75vh] mx-auto bg-white  overflow-y-auto">
        <div class="h-full flex flex-col lg:flex-row shadow-lg">
            <!-- left section -->
            <div class="h-full w-full lg:w-3/5 shadow-lg">
                <!-- header -->
                <div class="flex flex-row justify-between items-center px-5 mt-5">
                    <div class="text-gray-800">
                        <div class="font-bold text-xl">{{ getCompany()->name ?? '' }}</div>
                    </div>
                </div>
                <!-- end header -->
                <!-- search products -->
                <div class="flex px-2 flex-row relative mt-5">
                    <div class="absolute left-3 top-1 px-2 py-2 rounded-full bg-yellow-500 text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input
                        type="text"
                        class="bg-white text-gray-800 rounded-3xl shadow text-lg full w-full h-12 py-4 pl-16 transition-shadow focus:shadow-2xl focus:outline-none"
                        placeholder="Find item ..."
                        wire:model="search_items"
                    />
                </div>
                <!-- end search products -->
                <!-- categories -->
                <div class="flex flex-row items-center px-5 py-4 overflow-x-scroll text-center">
                    <button type="button" wire:click.prevent="selectCategories(0)"
                        @class([
                            'px-5 py-1 rounded-2xl text-sm mr-4 whitespace-nowrap',
                            'bg-yellow-500 text-white'=> $selected_categories == 0,
                            'font-semibold text-gray-800' => $selected_categories != 0
                        ])
                        >
                        All items
                    </button>
                    @foreach($categories as $category)
                    <button type="button" wire:click.prevent="selectCategories({{$category->id}})"
                        @class([
                            'px-5 py-1 rounded-2xl text-sm mr-4 whitespace-nowrap' ,
                            'bg-yellow-500 text-white'=> $category->id == $selected_categories,
                            'font-semibold text-gray-800' => $category->id != $selected_categories
                        ])
                        >
                        {{ $category->categories_name }}
                    </button>
                    @endforeach
                </div>
                <!-- end categories -->
                <!-- products -->
                <div class="h-2/3 grid grid-cols-3 gap-4 content-start px-5 mt-2 overflow-y-auto">
                    @foreach($products as $product)
                    <a href="#" wire:click.prevent.prevent="addToCart({{ $product->id }})" class="px-3 py-3 flex flex-col border border-gray-200 rounded-md h-36 justify-between">
                        <div>
                            <div class="font-bold text-gray-800">{{ $product->product_name }}</div>
                            <span class="font-light text-sm text-gray-400">150g</span>
                        </div>
                        <div class="flex flex-row justify-between items-center">
                            <span class="self-end font-bold text-lg text-yellow-500">RM{{ $product->price }}</span>
                            <img src="{{ isset($product['picture_url']) ? asset($product['picture_url']) : asset('asset/img/default-image.png') }}"
                                class=" h-14 w-14 object-cover rounded-md" alt="">
                        </div>
                        @if($selected_categories == 0)
                            <div class="text-gray-500">
                                {{ \Illuminate\Support\Str::limit($product->category->categories_name, 20) }}
                            </div>
                        @endif
                    </a>
                    @endforeach
                </div>
                <!-- end products -->
            </div>
            <!-- end left section -->
            <!-- right section -->
            <div class="flex flex-col h-full w-full lg:w-2/5">
                <div class="flex flex-row items-center justify-between px-5 mt-5">
                    <div class="font-bold text-xl text-gray-800">Current Order</div>
                    <div class="font-semibold">
                        <a href="#" wire:click.prevent="removeAll()" class="px-4 py-2 rounded-md bg-red-100 text-red-500">Clear All</a>
                        {{--                        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-800">Setting</span>--}}
                    </div>
                </div>
                <!-- end header -->
                <!-- order list -->
                <div class="grow px-5 py-4 mt-5 overflow-y-auto shadow-md">
                    @foreach($carts as $index => $cart)
                        <div class="flex flex-row justify-between items-center mb-4">
                            <a href="#" wire:click.prevent="removeItem({{ $index }})" class="px-3 py-1 rounded-md bg-red-500 hover:bg-red-600">x</a>
                            <div class="flex flex-row items-center w-2/5">
                                <img src="{{ isset($cart['picture_url']) ? asset($cart['picture_url']) : asset('asset/img/default-image.png') }}"
                                     class="w-10 h-10 object-cover rounded-md" alt="">
                                <span class="ml-4 font-semibold text-sm text-gray-800 text-gray-800">{{ $cart['product_name'] }}</span>
                            </div>
                            <div class="w-32 flex justify-between items-center">
                                <a href="#" wire:click.prevent="incrDecrQuantity({{ $index }}, {{ true }})" class="px-3 py-1 rounded-md bg-gray-300 text-gray-800 hover:bg-gray-400">-</a>
                                <span class="font-semibold mx-4 text-gray-800">{{ $cart['quantity'] }}</span>
                                <a href="#" wire:click.prevent="incrDecrQuantity({{ $index }})" class="px-3 py-1 rounded-md bg-gray-300 text-gray-800 hover:bg-gray-400">+</a>
                            </div>
                            <div class="font-semibold text-lg w-16 text-center text-gray-800">
                                RM{{ $cart['price'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- end order list -->
                <!-- totalItems -->
                <div class="px-5 mt-5">
                    <div class="py-4 rounded-md shadow-lg">
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm text-gray-800">Subtotal</span>
                            <span class="font-bold text-gray-800">RM{{ number_format($subtotal, 2, '.', ',') }}</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm text-gray-800">Discount</span>
                            <span class="font-bold text-gray-800">RM{{ number_format($discount, 2, '.', ',') }}</span>
                        </div>
                        {{--                        <div class=" px-4 flex justify-between ">--}}
                        {{--                            <span class="font-semibold text-sm text-gray-800">Service Charges (6%)</span>--}}
                        {{--                            <span class="font-bold text-gray-800">$2.25</span>--}}
                        {{--                        </div>--}}
                        <div class="border-t-2 mt-3 py-2 px-4 flex items-center justify-between">
                            <span class="font-semibold text-2xl text-gray-800">Total</span>
                            <span class="font-bold text-2xl text-gray-800">RM{{ number_format($total, 2, '.', ',') }}</span>
                        </div>
                    </div>
                </div>
                <!-- end total -->
                <!-- button pay-->
                <div class="px-5 my-5">
                    <button
                        @class([
                             "w-full px-4 py-4 rounded-md shadow-lg text-center text-white font-semibold",
                             'bg-gray-300 cursor-not-allowed' => count($carts) == 0,
                             'bg-yellow-500' => count($carts) != 0
                        ])
                        wire:click="$emit('openModal', 'pos.pay-modal', {{ json_encode(['carts' => $carts]) }})"
                        @if(count($carts) === 0) disabled @endif
                    >
                        PROCEED ORDER
                    </button>
                </div>
            </div>
            <!-- end right section -->
        </div>
    </div>
</div>
