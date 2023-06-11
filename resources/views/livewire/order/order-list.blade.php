<div>
    <div class="flex flex-col p-4 gap-4">
        <div class="px-4 flex flex-col justify-between items-center md:flex-row gap-4">
            <div class="flex px-2 flex-row relative">
                <div class="absolute left-3 top-1 px-2 py-2 rounded-full bg-yellow-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    class="bg-white text-gray-800 rounded-3xl shadow text-sm w-96 h-10 py-4 pl-12 transition-shadow focus:shadow-2xl focus:outline-none"
                    placeholder="Search order ..."
                    wire:model="search_items"
                />
            </div>
            <div class="flex gap-4">
                <button wire:click="selectListType('tile')" type="button" class="cursor-pointer w-10 w-10 text-yellow-500 border border-yellow-500 p-2 rounded @if($list_type == 'tile') bg-yellow-500 text-yellow-900 @endif hover:bg-yellow-500 hover:text-yellow-900 ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2"/><path d="M21 12H3M12 3v18"/>
                    </svg>
                </button>
                <button wire:click="selectListType('list')" type="button" class="cursor-pointer w-10 w-10 text-yellow-500 border border-yellow-500 p-2 rounded @if($list_type == 'list') bg-yellow-500 text-yellow-900 @endif hover:bg-yellow-500 hover:text-yellow-900 ">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"></line>
                        <line x1="8" y1="12" x2="21" y2="12"></line>
                        <line x1="8" y1="18" x2="21" y2="18"></line>
                        <line x1="3" y1="6" x2="3.01" y2="6"></line>
                        <line x1="3" y1="12" x2="3.01" y2="12"></line>
                        <line x1="3" y1="18" x2="3.01" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>
        <div class="">
            @if($list_type == 'list')
                <div class="flex-1 p-4">
                    <div class="flex flex-col">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                                <div class="overflow-hidden border border-gray-200 md:rounded-lg">
                                    <table class="bg-yellow-500 min-w-full divide-y divide-gray-200 ">
                                        <thead class="text-center">
                                            <tr>
                                                <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                                    <span>#</span>
                                                </th>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                                    Order Number
                                                </th>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                                    Items
                                                </th>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                                    Total
                                                </th>
                                                <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                                    Status
                                                </th>
                                                <th scope="col" class="w-48 px-4 py-3.5 text-sm font-normal text-yellow-900">
                                                    Action
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($orders as $order)
                                            <tr>
                                                <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                    <h2 class="font-medium text-gray-700">
                                                        {{ ($orders->currentpage()-1) * $orders->perpage() + $loop->index + 1 }}
                                                    </h2>
                                                </td>
                                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                    <h4 class="text-gray-700 dark:text-gray-700">{{ $order->order_no }}</h4>
                                                </td>
                                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                    <h4 class="text-gray-700 dark:text-gray-700">{{ $order->order_items->count() }}</h4>
                                                </td>
                                                <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                                    <h4 class="text-gray-700 dark:text-gray-700">RM {{ $order->total_amount }}</h4>
                                                </td>
                                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                    <div
                                                        @class([
                                                            "inline px-3 py-1 text-sm font-normal rounded-full text-emerald-500 gap-x-2",
                                                            "text-red-900 bg-red-100/60" => $order->status == \App\Enum\OrderStatus::UNPAID,
                                                            "text-green-900 bg-green-100/60" => $order->status == \App\Enum\OrderStatus::PAID
                                                        ])
                                                    >
                                                        {{ \App\Enum\OrderStatus::from($order->status->value)->getLabel() }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-4 text-sm whitespace-nowrap">
                                                    <div class="flex gap-2 justify-center">
                                                        <div class="group relative flex justify-center">
                                                            <a href="{{ route('order.show', $order->id) }}" class="w-10 h-10 p-2 text-yellow-500 border-2 border-yellow-500 rounded hover:bg-yellow-500 hover:text-yellow-900">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                                    <circle cx="12" cy="12" r="3"></circle>
                                                                </svg>
                                                            </a>
                                                            <span class="pointer-events-none absolute -top-8 w-max rounded bg-gray-900 px-2 py-1 text-sm font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">
                                                                View Order
                                                            </span>
                                                        </div>
                                                        <div class="group relative flex justify-center">
                                                            <button type="button" onclick="printOrder('{{ route('print.receipt', $order->id) }}')" class="w-10 h-10 p-2 text-gray-500 border-2 border-gray-500 rounded hover:bg-gray-500 hover:text-gray-900">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                                                    <rect x="6" y="14" width="12" height="8"></rect>
                                                                </svg>
                                                            </button>
                                                            <span class="pointer-events-none absolute -top-8 w-max rounded bg-gray-900 px-2 py-1 text-sm font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">
                                                                Print Receipt
                                                            </span>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-4 py-4 text-sm whitespace-nowrap text-center dark:text-white">
                                                    No order for now
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div @class([
                        "p-4 grid",
                        "grid-cols-1" => $orders->isEmpty(),
                        "grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" => $orders->isNotEmpty()
                    ])>
                    @forelse($orders as $order)
                        <div class="h-full w-full flex flex-col gap-4 rounded p-4 bg-white border border-gray-300">
                            <div class="flex justify-between">
                                <span class="bg-yellow-500 py-2 px-4 rounded-lg">
                                    <p class="font-bold text-yellow-900">{{ $order->order_no }}</p>
                                </span>
                                <div class="mb-2 px-2 rounded-full border-2 w-1/3 text-center self-end
                                        @if($order->status == \App\Enum\OrderStatus::UNPAID)
                                        text-red-500
                                        border-red-500
                                        @elseif($order->status == \App\Enum\OrderStatus::PAID)
                                        text-green-700
                                        border-green-700
                                        @else
                                        text-yellow-600
                                        border-yellow-600
                                        @endif
                                    ">
                                    {{ \App\Enum\OrderStatus::from($order->status->value)->getLabel() }}
                                </div>
                            </div>
                            <div class="flex-1">
                                <table class="w-full border-separate border-spacing-y-1">
                                    <thead class="border-b-2 border-b-yellow-500">
                                        <tr>
                                            <th class="text-left">Items:</th>
                                            <th>Qty:</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($order->order_items()->limit(3)->get() as $item)
                                        <tr class="bg-gray-100 text-gray-900">
                                            <td class="px-2 py-1 rounded-l-lg text-sm">
                                                {!! $item->product->product_name ?? $item->product()->withTrashed()->first()->product_name." <b>(Deleted)</b>" !!}
                                            </td>
                                            <td class="px-2 py-1 rounded-r-lg text-sm text-center">{{ $item->quantity }}</td>
                                        </tr>
                                    @endforeach
                                    @if($order->order_items->count() > 3)
                                        <tr>
                                            <td colspan="2" class="px-2 py-1 rounded-r-lg text-sm text-right">+{{ $order->order_items->count() - 3 }} item(s)</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mt-2">
                                    <p class="text-yellow-600 text-sm font-bold">Subtotal:</p>
                                    <p class="text-gray-900 text-sm">RM{{ $order->subtotal_amount }}</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-yellow-600 text-sm font-bold">Discount:</p>
                                    <p class="text-gray-900 text-sm">RM{{ $order->discount_amount }}</p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-yellow-600 text-sm font-bold">Total:</p>
                                    <p class="text-gray-900 text-sm">RM{{ $order->total_amount }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 justify-end">
                                <div class="group relative flex justify-center">
                                    <a href="{{ route('order.show', $order->id) }}" class="w-10 h-10 p-2 text-yellow-500 border-2 border-yellow-500 rounded hover:bg-yellow-500 hover:text-yellow-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <span class="pointer-events-none absolute -top-8 w-max rounded bg-gray-900 px-2 py-1 text-sm font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">
                                        View Order
                                    </span>
                                </div>
                                <div class="group relative flex justify-center">
                                    <button type="button" onclick="printOrder('{{ route('print.receipt', $order->id) }}')" class="w-10 h-10 p-2 text-gray-500 border-2 border-gray-500 rounded hover:bg-gray-500 hover:text-gray-900">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                            <rect x="6" y="14" width="12" height="8"></rect>
                                        </svg>
                                    </button>
                                    <span class="pointer-events-none absolute -top-8 w-max rounded bg-gray-900 px-2 py-1 text-sm font-medium text-gray-50 opacity-0 shadow transition-opacity group-hover:opacity-100">
                                        Print Receipt
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="w-full text-center dark:text-white">No order for now</p>
                    @endforelse
                </div>
            @endif
            @if($orders->hasPages())
                <div class="mt-4 px-4">
                    {{ $orders->links() }}
                </div>
            @endif
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
    </div>
</div>
