<div>
    <form method="post" action="{{ $self_order ? route('order.store.selfOrder') : route('order.store') }}">
        @csrf
        <!--Header and Close Button-->
        <div class="mx-5 mt-5 flex justify-between items-center">
            <div class="flex flex-col">
                @if(!is_null($selected_table_no))
                <p class="font-bold">
                    Table No: {{ $selected_table_no }}
                    <input wire:model="selected_table_id" type="hidden" name="table_id">
                </p>
                @endif
                <p class="font-bold">
                    @if($company_id)
                        <input type="hidden" name="company_id" value="{{ $company_id }}">
                    @endif
                </p>
            </div>
            <button type="button" class="bg-gray-200 py-1 px-2 rounded-md font-sm hover:bg-gray-300" wire:click.prevent="$emit('closeModal')">X</button>
        </div>
        <!--End Header and Close Button-->
        <div class="h-[80vh] mx-5 mb-5 flex flex-col">
            <div class="grow overflow-y-auto">
                <table class="w-full text-sm border-separate border-spacing-y-3">
                    <thead class="bg-yellow-500 text-white text-center sticky top-0">
                    <tr>
                        <th class="p-3 rounded-l-lg">#</th>
                        <th class="p-3">Item</th>
                        <th class="p-3">Price</th>
                        <th class="p-3">Quantity</th>
                        <th class="p-3 rounded-r-lg">Subtotal</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($carts as $index => $cart)
                        <tr class="bg-gray-100 text-center text-gray-900">
                            <td class="p-3 rounded-l-lg">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3">
                                {{ $cart['product_name'] }}
                                <input wire:model="carts.{{ $index }}.id" type="hidden" name="order_items[{{$index}}][product_id]">
                            </td>
                            <td class="p-3">
                                RM{{ number_format($cart['price'], 2, '.', ',') }}
                                <input wire:model="carts.{{ $index }}.price" type="hidden" name="order_items[{{$index}}][price]">
                            </td>
                            <td class="p-3">
                                {{ $cart['quantity'] }}
                                <input wire:model="carts.{{ $index }}.quantity" type="hidden" name="order_items[{{$index}}][quantity]">
                            </td>
                            <td class="p-3 rounded-r-lg">
                                RM{{ number_format($cart['price'] * $cart['quantity'], 2, '.', ',') }}
                                <input type="hidden" name="order_items[{{$index}}][subtotal]" value="{{ number_format($cart['price'] * $cart['quantity'], 2, '.', ',') }}">
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="flex flex-row gap-6 pt-4">
                <div class="w-full">
                    <div class="py-4 rounded-md">
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm text-gray-800">Subtotal</span>
                            <span class="font-bold text-gray-800">RM{{ number_format($subtotal, 2, '.', ',') }}</span>
                            <input wire:model="subtotal" type="hidden" name="subtotal_amount">
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm text-gray-800">Discount</span>
                            <span class="font-bold text-gray-800">RM{{ number_format($discount, 2, '.', ',') }}</span>
                            <input wire:model="discount" type="hidden" name="discount_amount">
                        </div>
                        {{--                    <div class=" px-4 flex justify-between ">--}}
                        {{--                        <span class="font-semibold text-sm text-gray-800">Service Charges (6%)</span>--}}
                        {{--                        <span class="font-bold text-gray-800">$2.25</span>--}}
                        {{--                    </div>--}}
                        <div class="border-y-2 mt-3 py-2 px-4 flex items-center justify-between">
                            <span class="font-semibold text-2xl text-gray-800">Total</span>
                            <span class="font-bold text-2xl text-gray-800">RM{{ number_format($total, 2, '.', ',') }}</span>
                            <input wire:model="total" type="hidden" name="total_amount">
                        </div>
                    </div>
                </div>
                <div class="w-full flex flex-col gap-4">
                    <div class="flex flex-row items-center px-4 py-2">
                        <p class="font-bold text-lg mr-4 whitespace-nowrap">PAY AMOUNT</p>
                        @if($self_order)
                            <p class="font-bold text-lg">RM{{ $pay_amount }}</p>
                            <input wire:model="pay_amount" type="hidden" name="paid_amount">
                        @else
                            <input wire:model="pay_amount" class="flex-1 rounded-md" type="number" name="paid_amount" step="0.01">
                        @endif
                    </div>
                    <div>
                        <select class="w-full text-sm rounded" name="payment_method">
                            @foreach($payment_types as $key => $type)
                                <option value="{{ $key }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-row justify-between items-center px-4 py-2 bg-yellow-300 rounded-md shadow-md">
                        <p class="font-bold text-lg mr-4 text-yellow-900">CHANGE</p>
                        <p class="font-bold text-lg mr-4 text-yellow-700">RM{{ number_format($change, 2, '.', ',') }}</p>
                    </div>
                    <button type="submit" name="pay" class="w-full px-4 py-2 border rounded-md text-white shadow-md @if($pay_amount < $total) bg-gray-300 cursor-not-allowed @else bg-yellow-500 @endif" @if($pay_amount < $total) disabled @endif>PAY</button>
                </div>
            </div>
        </div>
    </form>
</div>
