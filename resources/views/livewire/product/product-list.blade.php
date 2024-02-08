<div>
    <div class="flex flex-col text-white">
        <div class="p-4 flex flex-row justify-between items-center">
            <button wire:click="$emit('openModal', 'product.add-edit-product-modal')" type="button" class="text-sm px-4 py-2 bg-yellow-500 rounded-md whitespace-nowrap">Add Products</button>
            <div class="flex px-2 flex-row relative">
                <div class="absolute left-3 top-1 px-2 py-2 rounded-full bg-yellow-500 text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    type="text"
                    class="bg-white text-gray-800 rounded-3xl shadow text-sm w-96 h-9 py-4 pl-12 transition-shadow focus:shadow-2xl focus:outline-none"
                    placeholder="Find item ..."
                    wire:model="search_items"
                />
            </div>
        </div>
        <div class="flex p-4 border-y border-yellow-700 items-center text-gray-900">
            <div class="font-bold text-xl mr-4">
                Categories:
            </div>
            <div class="flex gap-4 w-full overflow-x-auto">
                <a href="#" wire:click.prevent="select_category(0)"
                   class="px-4 py-2 rounded-lg whitespace-nowrap @if($selected_category == 0) bg-yellow-500 @endif">
                    All Items
                </a>
                @foreach($categories as $category)
                    <a href="#" wire:click.prevent="select_category({{$category->id}})"
                       class="px-4 py-2 rounded-lg whitespace-nowrap @if($selected_category == $category->id) bg-yellow-500 @endif">
                        {{ $category->categories_name }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="flex-1 p-4">
            <div class="flex flex-col">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-yellow-500">
                                <tr>
                                    <th scope="col" class="py-3.5 px-4 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                        <button class="flex items-center gap-x-3 focus:outline-none">
                                            <span>#</span>
                                        </button>
                                    </th>
                                    <th scope="col" class="px-12 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                        Name
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                        Price
                                    </th>
                                    <th scope="col" class="px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                        Category
                                    </th>
                                    <th scope="col" class="w-48 px-4 py-3.5 text-sm font-normal text-left rtl:text-right text-yellow-900">
                                        Action
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($products as $product)
                                    <tr>
                                    <td class="px-4 py-4 text-sm font-medium whitespace-nowrap">
                                        <h2 class="font-medium text-gray-800">
                                            {{ ($products->currentpage()-1) * $products->perpage() + $loop->index + 1 }}
                                        </h2>
                                    </td>
                                    <td class="px-12 py-4 text-sm font-medium whitespace-nowrap">
                                        <h4 class="text-gray-700">{{ $product->product_name }}</h4>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <h4 class="text-gray-700">RM{{ $product->price }}</h4>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        @php
                                        $color = !isset($product->category->categories_name) ? 'gray' : 'emerald';
                                        @endphp
                                        <div class="inline px-3 py-1 text-sm font-normal rounded-full text-{{$color}}-500 gap-x-2 bg-{{$color}}-100/60">
                                            {{ $product->category->categories_name ?? 'No categories' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm whitespace-nowrap">
                                        <div class="flex flex-row gap-4">
                                            <a wire:click="$emit('openModal', 'product.add-edit-product-modal', {{ json_encode(['product' => $product, 'is_edit' => true]) }})" href="#" class="text-sm text-gray-500 border border-gray-500 rounded-lg p-2 hover:bg-gray-500 hover:text-gray-300">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M20 14.66V20a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h5.34"></path>
                                                    <polygon points="18 2 22 6 12 16 8 16 8 12 18 2"></polygon>
                                                </svg>
                                            </a>
                                            <form method="post" action="{{ route('product.destroy', $product->id) }}">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="text-sm text-red-600 border border-red-600 rounded-lg p-2 hover:bg-red-400">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17">
                                                        </line><line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-4 text-sm whitespace-nowrap text-center">
                                            No product in this Category
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if($products->hasPages())
            <div class="mt-4">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

