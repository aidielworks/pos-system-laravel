<div>
    <div class="flex px-2 flex-row relative mb-3">
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
    <div class="h-[calc(100vh-144px-48px-48px)] flex gap-4">
        <div class="w-1/3 flex flex-col gap-4">
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
        <div class="w-2/3 flex flex-col gap-4 overflow-x-scroll">
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
    </div>

    {{--    TODO:: view cart and payment button --}}
    <div class="fixed bottom-4 right-4">
{{--        <button--}}
{{--            @class([--}}
{{--                "w-full px-4 py-4 rounded-md shadow-lg text-center font-semibold",--}}
{{--                'bg-gray-300 cursor-not-allowed text-gray-100' => count($carts) == 0,--}}
{{--                'bg-yellow-500 text-white hover:bg-yellow-700 hover:text-yellow-500' => count($carts) != 0--}}
{{--            ])--}}
{{--            wire:click.prevent="$emit('openModal', 'pos.pay-modal', {{ json_encode(['carts' => $carts, 'selected_table_no' => $selected_table_no, 'selected_table_id' => $selected_table_id, 'self_order' => $self_order, 'company_id' => $company_id]) }})"--}}
{{--            @if(count($carts) === 0) disabled @endif--}}
{{--        >--}}
{{--            PAY--}}
{{--        </button>--}}
        @if(array_sum(array_column($carts, "quantity")) > 0)
            <a href="{{ route('order.view.selfOrder', ['order' => encrypt(['session_key' => $current_table_cart_session_key, 'table_id' => $selected_table_id, 'company_id' => $company_id])]) }}" target=”_blank” class="bg-yellow-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full shadow-md">
                Cart: {{ array_sum(array_column($carts, "quantity")) }}
            </a>
       @endif

    </div>
</div>
