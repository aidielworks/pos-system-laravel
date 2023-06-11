<div>
    <form method="post" action="{{ route('order.pay', $order['id']) }}">
        @csrf
        <!--Header and Close Button-->
        <div class="mx-5 mt-5 flex justify-between items-center">
            <p class="font-bold">
                Order: {{ $order['order_no'] }}
            </p>
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
                    @foreach($order_items as $index => $item)
                        <tr class="bg-gray-100 text-center text-gray-900">
                            <td class="p-3 rounded-l-lg">
                                {{ $index + 1 }}
                            </td>
                            <td class="p-3">
                                {{ $item->product()->withTrashed()->first()->product_name }}
                            </td>
                            <td class="p-3">
                                RM{{ $item['price'] ?? 0 }}
                            </td>
                            <td class="p-3">
                                {{ $item['quantity'] ?? 0 }}
                            </td>
                            <td class="p-3 rounded-r-lg">
                                RM{{ $item['subtotal'] ?? 0 }}
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
                            <span class="font-bold text-gray-800">RM{{ $order['subtotal_amount'] }}</span>
                        </div>
                        <div class=" px-4 flex justify-between ">
                            <span class="font-semibold text-sm text-gray-800">Discount</span>
                            <span class="font-bold text-gray-800">RM{{ $order['discount_amount'] }}</span>
                        </div>
                        {{--                    <div class=" px-4 flex justify-between ">--}}
                        {{--                        <span class="font-semibold text-sm text-gray-800">Service Charges (6%)</span>--}}
                        {{--                        <span class="font-bold text-gray-800">$2.25</span>--}}
                        {{--                    </div>--}}
                        <div class="border-y-2 mt-3 py-2 px-4 flex items-center justify-between">
                            <span class="font-semibold text-2xl text-gray-800">Total</span>
                            <span class="font-bold text-2xl text-gray-800">RM{{ $order['total_amount'] }}</span>
                        </div>
                    </div>
                </div>
                <div class="w-full">
                    <div class="flex flex-row items-center px-4 py-2 mb-2">
                        <p class="font-bold text-lg mr-4">PAY AMOUNT</p>
                        <input wire:model="pay_amount" class="grow rounded-md" type="number" name="paid_amount" step="0.01">
                    </div>
                    <div class="flex flex-row justify-between items-center px-4 py-2 bg-yellow-300 rounded-md mb-4 shadow-md">
                        <p class="font-bold text-lg mr-4 text-yellow-900">CHANGE</p>
                        <p class="font-bold text-lg mr-4 text-yellow-700">RM{{ number_format($change, 2, '.', ',') }}</p>
                    </div>
                    <div class="flex gap-4">
                        <button type="submit" name="pay" class="w-full px-4 py-2 border rounded-md text-white shadow-md @if($pay_amount < $total) bg-gray-300 cursor-not-allowed @else bg-yellow-500 @endif" @if($pay_amount < $total) disabled @endif>PAY</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
