<div>
    @if(!$show_pos)
        <div class="bg-white mt-3 p-4">
            <div class="flex gap-4 justify-between items-center mb-6">
                <p class="font-bold text-xl">List of available tables</p>
                <button wire:click="chooseType({{ app\Http\Livewire\Pos\Pos::TYPE_TAKEAWAY }})" type="button" class="px-3 py-2 border border-yellow-500 bg-yellow-500 text-white rounded-lg hover:bg-white hover:text-yellow-500">
                    Take Away
                </button>
            </div>
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7">
                @foreach ($available_tables as $index => $table)
                    @php
                        $unpaid_table = $table->orders()->where('status', App\Enum\OrderStatus::UNPAID);
                        $order = $unpaid_table->first();
                    @endphp

                    @if(!$unpaid_table->exists())
                        <a wire:click.prevent="chooseTable({{ $table->id }})" wire:key="{{ $index }}" href="#">
                    @else
                        <a href="{{ route('order.show', $order->id) }}">
                            @endif
                            <div class="relative w-32 h-32 border rounded-full flex flex-col justify-center items-center">
                                <p class="text-3xl @if($unpaid_table->exists()) text-gray-500 @else text-black @endif">{{ $table->table_no }}</p>
                                <p
                                    @class([
                                        'px-3 py-1 text-xs rounded-full border text-center',
                                        'bg-gray-200 text-gray-400' => $unpaid_table->exists(),
                                        'bg-gray-400 text-white' => session()->has('cart_session_' . $table->id) && count(session()->get('cart_session_' . $table->id)) > 0,
                                        'bg-green-500 text-white' => $table->status == App\Enum\TableStatus::AVAILABLE && !$unpaid_table->exists() && !(session()->has('cart_session_' . $table->id) && count(session()->get('cart_session_' . $table->id)) > 0),
                                        'bg-red-500 text-white' => $table->status == App\Enum\TableStatus::NOT_AVAILABLE,
                                        'bg-yellow-500 text-white' => $table->status == App\Enum\TableStatus::RESERVED,
                                    ])
                                >
                                    @if($unpaid_table->exists())
                                        Occupied
                                    @elseif(session()->has('cart_session_' . $table->id) && count(session()->get('cart_session_' . $table->id)) > 0)
                                        Ongoing
                                    @else
                                        {{ $table->status->getLabel() }}
                                    @endif
                                </p>
                            </div>
                        </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($show_pos)
        <form action="{{ route('order.store') }}" method="post">
            @csrf
            <div class="h-screen md:h-[75vh] mx-auto bg-white  overflow-y-auto">
                <div class="h-full flex flex-col lg:flex-row shadow-lg">
                    <!-- left section -->
                    <div class="h-full w-full lg:w-3/5 shadow-lg">
                        <!-- header -->

                            <div class="flex flex-row gap-4 items-center px-5 mt-5">
                                @if(!$self_order)
                                <button wire:click="clearSteps" type="button" class="px-3 py-2 rounded-md shadow-lg text-center font-semibold bg-yellow-500 text-white hover:bg-yellow-700 hover:text-yellow-500">
                                    Back
                                </button>
                                @endif
                                <div class="text-gray-800 w-2/3">
                                    <div class="font-bold text-xl">{{ getCompany($company_id)->name ?? '' }}</div>
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
                        <div class="flex items-center px-5 py-4">
                            @if ($selected_type == app\Http\Livewire\Pos\Pos::TYPE_DINE_IN)
                                <p class="font-bold">
                                    Table No: {{ $selected_table_no ?? 0 }}
                                </p>
                                <input type="hidden" name="table_id" wire:model="selected_table_id">
                            @else
                                <p class="font-bold">
                                    Take Away
                                </p>
                            @endif
                        </div>
                        <div class="flex flex-row items-center justify-between px-5">
                            <div class="font-bold text-xl text-gray-800">Current Order</div>
                            <div class="font-semibold">
                                <a href="#" wire:click.prevent="removeAll()" class="px-4 py-2 rounded-md bg-red-100 text-red-500">Clear All</a>
                                {{--                        <span class="px-4 py-2 rounded-md bg-gray-100 text-gray-800">Setting</span>--}}
                            </div>
                        </div>
                        <!-- end header -->
                        <!-- order list -->
                        <div class="flex-1 px-5 py-4 mt-5 overflow-y-auto shadow-md">
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
                                    <input wire:model="carts.{{ $index }}.id" type="hidden" name="order_items[{{$index}}][product_id]">
                                    <input wire:model="carts.{{ $index }}.price" type="hidden" name="order_items[{{$index}}][price]">
                                    <input wire:model="carts.{{ $index }}.quantity" type="hidden" name="order_items[{{$index}}][quantity]">
                                    <input type="hidden" name="order_items[{{$index}}][subtotal]" value="{{ number_format($cart['price'] * $cart['quantity'], 2, '.', ',') }}">
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
                            <input wire:model="subtotal" type="hidden" name="subtotal_amount">
                            <input wire:model="discount" type="hidden" name="discount_amount">
                            <input wire:model="total" type="hidden" name="total_amount">
                        </div>
                        <!-- end total -->
                        <!-- button pay-->
                        <div class="flex flex-row gap-4 px-5 my-5">
                            @if ($selected_type == app\Http\Livewire\Pos\Pos::TYPE_DINE_IN && !$self_order)
                                <button type="submit" name="place_order"
                                    @class([
                                        'w-full px-4 py-2 rounded-md shadow-md font-semibold',
                                        'bg-gray-300 cursor-not-allowed text-gray-100' => count($carts) == 0,
                                        'border border-yellow-500 bg-white text-yellow-500 hover:bg-yellow-500 hover:text-white' => count($carts) != 0
                                    ])
                                    @if(count($carts) === 0) disabled @endif
                                >
                                    PLACE ORDER
                                </button>
                            @endif
                            <button
                                @class([
                                    "w-full px-4 py-4 rounded-md shadow-lg text-center font-semibold",
                                    'bg-gray-300 cursor-not-allowed text-gray-100' => count($carts) == 0,
                                    'bg-yellow-500 text-white hover:bg-yellow-700 hover:text-yellow-500' => count($carts) != 0
                                ])
                                wire:click.prevent="$emit('openModal', 'pos.pay-modal', {{ json_encode(['carts' => $carts, 'selected_table_no' => $selected_table_no, 'selected_table_id' => $selected_table_id, 'self_order' => $self_order, 'company_id' => $company_id]) }})"
                                @if(count($carts) === 0) disabled @endif
                            >
                                PAY
                            </button>
                        </div>
                    </div>
                    <!-- end right section -->
                </div>
            </div>
        </form>
    @endif
</div>
