<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        {{ __('Self Order') }}
        </h2>
    </x-slot>
    <div class="px-4">
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
                    <td>
                        <input type="checkbox"  name="">
                    </td>
                    <td class="p-3">
                        {{ $cart['product_name'] }}
                        <input type="hidden" name="order_items[{{$index}}][product_id]">
                    </td>
                    <td class="p-3">
                        RM{{ number_format($cart['price'], 2, '.', ',') }}
                        <input type="hidden" name="order_items[{{$index}}][price]">
                    </td>
                    <td class="p-3">
                        {{ $cart['quantity'] }}
                        <input type="hidden" name="order_items[{{$index}}][quantity]">
                    </td>
                    <td class="p-3 rounded-r-lg">
                        RM{{ number_format($cart['price'] * $cart['quantity'], 2, '.', ',') }}
                        <input type="hidden" name="order_items[{{$index}}][subtotal]" value="{{ number_format($cart['price'] * $cart['quantity'], 2, '.', ',') }}">
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <a href="{{ url($table->url) }}" class="bg-yellow-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">Back</a>
    </div>

</x-guest-layout>
