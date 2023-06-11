<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
            {{ __('Order') }}
        </h2>
    </x-slot>
    <div class="my-5">
        <div class="flex justify-center max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="w-2/3 h-[75vh] p-4 flex flex-col gap-4 bg-white ">
                <!-- Back button and status -->
                <div class="flex justify-between items-center">
                    <a href="{{ route('order.index') }}" class="px-4 py-1 border-2 border-gray-500 rounded-lg hover:bg-gray-500 hover:text-white">Back</a>
                    <div
                    @class([
                        'px-2 rounded-full border-2 w-24 text-center self-end',
                        'text-red-500 border-red-500' => $order->status == \App\Enum\OrderStatus::UNPAID,
                        'text-green-700 border-green-700' => $order->status == \App\Enum\OrderStatus::PAID
                    ])
                    ">
                        {{ $order->status->getLabel() }}
                    </div>
                </div>
                <!-- Back button and status -->

                <!-- Order no and Order datetime -->
                <div class="flex flex-row justify-between items-center text-right">
                    <span class="bg-yellow-500 px-4 py-2 rounded-lg">
                        <p class="font-bold text-yellow-900">{{ $order->order_no }}</p>
                    </span>
                    <div>
                        <span class="font-bold">Order created:</span> {{ $order->created_at }}
                    </div>
                </div>
                <!-- Order no and Order datetime -->

                <!-- Orders-->
                <div class="flex-1 overflow-y-auto border-y-2 border-gray-500">
                    <table class="w-full border-separate border-spacing-y-3">
                        <thead class="sticky top-0">
                        <tr>
                            <th class="bg-yellow-500 px-4 py-2 text-left rounded-l-lg">Items:</th>
                            <th class="bg-yellow-500 px-4 py-2">Price:</th>
                            <th class="bg-yellow-500 px-4 py-2">Qty:</th>
                            <th class="bg-yellow-500 px-4 py-2 rounded-r-lg">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->order_items as $item)
                            <tr class="bg-gray-200 text-gray-900">
                                <td class="px-4 py-2 rounded-l-lg">
                                    {!! $item->product->product_name ?? $item->product()->withTrashed()->first()->product_name." <b>(Deleted)</b>" !!}
                                </td>
                                <td class="px-4 py-2 text-center">RM{{ $item->price }}</td>
                                <td class="px-4 py-2 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-2 text-center rounded-r-lg">RM{{ $item->subtotal }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Orders-->

                <!-- Subtotal, Discount, Total -->
                <div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-900 font-bold">Subtotal:</p>
                        <p class="text-gray-900">RM{{ number_format($order->subtotal_amount, 2, '.', ',') }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-900 font-bold">Discount:</p>
                        <p class="text-gray-900">RM{{ number_format($order->discount_amount, 2, '.', ',') }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-gray-900 font-bold">Total:</p>
                        <p class="text-gray-900">RM{{ number_format($order->total_amount, 2, '.', ',') }}</p>
                    </div>
                </div>
                <!-- Subtotal, Discount, Total -->

                <!-- Action Button -->
                <div class="flex gap-4 justify-end">
                    @if($order->status == \App\Enum\OrderStatus::UNPAID)
                        <button onclick="Livewire.emit('openModal', 'order.pay-order-modal', {{ json_encode(['order_id' => $order->id]) }})" type="button" class="h-10 px-4 py-2 text-red-600 border-2 border-red-600 rounded hover:bg-red-600 hover:text-red-900">
                            Pay
                        </button>
                        <button onclick="Livewire.emit('openModal', 'order.add-order-item-modal', {{ json_encode(['order' => $order]) }})" type="button" class="h-10 px-4 py-2 text-green-600 border-2 border-green-600 rounded hover:bg-green-600 hover:text-green-900">
                            Add Order
                        </button>
                    @endif
                    <div class="group relative flex justify-center">
                        <button type="button" onclick="printOrder('{{ route('print.receipt', $order->id) }}')" class="w-10 h-10 p-2 text-gray-500 border-2 border-gray-500 rounded hover:bg-gray-500 hover:text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                <rect x="6" y="14" width="12" height="8"></rect>
                            </svg>
                        </button>
                        <span class="pointer-events-none absolute -top-9 w-max rounded bg-gray-900 px-2 py-1 font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">
                            Print Receipt
                        </span>
                    </div>
                </div>
                <!-- Action Button -->
            </div>
{{--            <div class="flex justify-center h-[80vh] gap-4 overflow-hidden shadow-sm sm:rounded-lg">--}}
{{--                <div class="h-full w-2/3 flex flex-col justify-between rounded p-4 bg-gray-100">--}}
{{--                    <div>--}}
{{--                        <div class="flex justify-between items-center">--}}
{{--                            <a href="{{ route('order.index') }}" class="px-4 py-1 border-2 border-gray-500 rounded-lg hover:bg-gray-500 hover:text-white">Back</a>--}}
{{--                            <div--}}
{{--                                @class([--}}
{{--                                    'px-2 rounded-full border-2 w-24 text-center self-end',--}}
{{--                                    'text-red-500 border-red-500' => $order->status == \App\Enum\OrderStatus::UNPAID,--}}
{{--                                    'text-green-700 border-green-700' => $order->status == \App\Enum\OrderStatus::PAID--}}
{{--                                ])--}}
{{--                            ">--}}
{{--                                {{ $order->status->getLabel() }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="flex flex-row justify-between items-center py-4 text-right">--}}
{{--                            <span class="bg-yellow-500 py-2 px-4 rounded-lg">--}}
{{--                                <p class="font-bold text-yellow-900">{{ $order->order_no }}</p>--}}
{{--                            </span>--}}
{{--                            <div><span class="font-bold">Order created:</span> {{ $order->created_at }}</div>--}}
{{--                        </div>--}}
{{--                        <div class="flex flex-col">--}}
{{--                            <div class="h-[50vh] overflow-y-auto border-y-2 border-gray-500">--}}
{{--                                <table class="w-full border-separate border-spacing-y-3">--}}
{{--                                    <thead class="sticky top-0">--}}
{{--                                        <tr>--}}
{{--                                            <th class="bg-yellow-500 px-4 py-2 text-left rounded-l-lg">Items:</th>--}}
{{--                                            <th class="bg-yellow-500 px-4 py-2">Price:</th>--}}
{{--                                            <th class="bg-yellow-500 px-4 py-2">Qty:</th>--}}
{{--                                            <th class="bg-yellow-500 px-4 py-2 rounded-r-lg">Subtotal</th>--}}
{{--                                        </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @foreach($order->order_items as $item)--}}
{{--                                        <tr class="bg-gray-200 text-gray-900">--}}
{{--                                            <td class="px-4 py-2 rounded-l-lg">{{ $item->product->product_name }}</td>--}}
{{--                                            <td class="px-4 py-2 text-center">RM{{ $item->price }}</td>--}}
{{--                                            <td class="px-4 py-2 text-center">{{ $item->quantity }}</td>--}}
{{--                                            <td class="px-4 py-2 text-center rounded-r-lg">RM{{ $item->subtotal }}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                            <div class="flex justify-between items-center mt-4">--}}
{{--                                <p class="text-gray-900 font-bold">Subtotal:</p>--}}
{{--                                <p class="text-gray-900">RM{{ number_format($order->subtotal_amount, 2, '.', ',') }}</p>--}}
{{--                            </div>--}}
{{--                            <div class="flex justify-between items-center">--}}
{{--                                <p class="text-gray-900 font-bold">Discount:</p>--}}
{{--                                <p class="text-gray-900">RM{{ number_format($order->discount_amount, 2, '.', ',') }}</p>--}}
{{--                            </div>--}}
{{--                            <div class="flex justify-between items-center">--}}
{{--                                <p class="text-gray-900 font-bold">Total:</p>--}}
{{--                                <p class="text-gray-900">RM{{ number_format($order->total_amount, 2, '.', ',') }}</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="mt-4 flex gap-4 justify-end">--}}
{{--                        @if($order->status == \App\Enum\OrderStatus::UNPAID)--}}
{{--                            <button onclick="Livewire.emit('openModal', 'order.pay-order-modal', {{ json_encode(['order' => $order]) }})" type="button" class="h-10 px-4 py-2 text-red-600 border-2 border-red-600 rounded hover:bg-red-600 hover:text-red-900">--}}
{{--                                Pay--}}
{{--                            </button>--}}
{{--                            <button onclick="Livewire.emit('openModal', 'order.add-order-item-modal', {{ json_encode(['order' => $order]) }})" type="button" class="h-10 px-4 py-2 text-green-600 border-2 border-green-600 rounded hover:bg-green-600 hover:text-green-900">--}}
{{--                                Add Order--}}
{{--                            </button>--}}
{{--                        @endif--}}
{{--                        <div class="group relative flex justify-center">--}}
{{--                            <button type="button" onclick="printOrder('{{ route('print.receipt', $order->id) }}')" class="w-10 h-10 p-2 text-gray-500 border-2 border-gray-500 rounded hover:bg-gray-500 hover:text-gray-900">--}}
{{--                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>--}}
{{--                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>--}}
{{--                                    <rect x="6" y="14" width="12" height="8"></rect>--}}
{{--                                </svg>--}}
{{--                            </button>--}}
{{--                            <span class="pointer-events-none absolute -top-9 w-max rounded bg-gray-900 px-2 py-1 font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">--}}
{{--                                Print Receipt--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>

    <script>
        function makeHttpObject() {
            if("XMLHttpRequest" in window)return new XMLHttpRequest();
            else if("ActiveXObject" in window)return new ActiveXObject("Msxml2.XMLHTTP");
        }

        function printOrder(url){
            const request = makeHttpObject();
            request.open("GET", url, true);
            request.send(null);
            request.onreadystatechange = function() {
                if (request.readyState == 4) {
                    const frame1 = document.createElement('iframe');
                    frame1.name = "frame1";
                    frame1.style.position = "absolute";
                    frame1.style.top = "-1000000px";
                    document.body.appendChild(frame1);
                    const frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
                    frameDoc.document.open();
                    frameDoc.document.write(request.responseText);
                    frameDoc.document.close();
                    setTimeout(function () {
                        window.frames["frame1"].focus();
                        window.frames["frame1"].print();
                        document.body.removeChild(frame1);
                    }, 500);
                    return false;
                }
            };
        }
    </script>

    @if(session()->has('print-order'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const request = makeHttpObject();
                request.open("GET", '{!! url('/order/print?items='.urlencode(json_encode(session()->get('print-order'))).'&id='.$order->id) !!}', true);
                request.send(null);
                request.onreadystatechange = function() {
                    if (request.readyState == 4) {
                        const frame1 = document.createElement('iframe');
                        frame1.name = "frame1";
                        frame1.style.position = "absolute";
                        frame1.style.top = "-1000000px";
                        document.body.appendChild(frame1);
                        const frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
                        frameDoc.document.open();
                        frameDoc.document.write(request.responseText);
                        frameDoc.document.close();
                        setTimeout(function () {
                            window.frames["frame1"].focus();
                            window.frames["frame1"].print();
                            document.body.removeChild(frame1);
                        }, 500);
                        return false;
                    }
                };
            });

            function makeHttpObject() {
                if("XMLHttpRequest" in window)return new XMLHttpRequest();
                else if("ActiveXObject" in window)return new ActiveXObject("Msxml2.XMLHTTP");
            }
        </script>
    @endif

    @if(session()->has('print-receipt'))
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                printOrder('{{ route('print.receipt', $order->id) }}');
            });

            function makeHttpObject() {
                if("XMLHttpRequest" in window)return new XMLHttpRequest();
                else if("ActiveXObject" in window)return new ActiveXObject("Msxml2.XMLHTTP");
            }
        </script>
    @endif
</x-app-layout>
