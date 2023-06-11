<div>
    <div class="container p-4">
       <div class="flex justify-between items-center border-b border-yellow-500">
           <p class="text-lg font-bold">
               {{ $is_edit ? 'Update' :'Create' }} Product
           </p>
           <button class="mb-2 bg-gray-200 py-1 px-2 rounded-md font-sm hover:bg-gray-300" wire:click="$emit('closeModal')">X</button>
       </div>
        <form method="post" action="{{ $is_edit ? route('product.update', $product['id']) : route('product.store') }}" enctype="multipart/form-data">
            @csrf
            @if($is_edit) @method('patch') @endif
            <div class="my-4">
                <div class="flex items-center">
                    <img class="mr-4 h-20 w-20 rounded-lg object-cover object-center"
                         alt="Image placeholder"
                         src="{{ isset($product['picture_url']) ? asset($product['picture_url']) : asset('asset/img/default-image.png') }}">
                    <div class="flex-1">
                        <label
                            for="product_name"
                            class="mb-2 block text-base font-medium text-[#07074D]"
                        >
                            Product Name
                        </label>
                        <input
                            wire:model="product.product_name"
                            type="text"
                            name="product_name"
                            id="product_name"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                    </div>
                </div>
            </div>

            <div class="my-2">
                <label
                    for="description"
                    class="mb-2 block text-base font-medium text-[#07074D]"
                >
                    Description
                </label>
                <textarea
                    wire:model="product.description"
                    name="description"
                    id="description"
                    rows="5"
                    class="w-full resize-none rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                ></textarea>
            </div>

            <div class="flex flex-col gap-4 md:flex-row">
                <div class="my-2 w-full">
                    <label
                        for="price"
                        class="mb-2 block text-base font-medium text-[#07074D]"
                    >
                        Price
                    </label>
                    <input
                        wire:model="product.price"
                        type="number"
                        min="0"
                        step="0.01"
                        name="price"
                        id="price"
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    />
                </div>
                <div class="my-2 w-full">
                    <label
                        for="category_id"
                        class="mb-2 block text-base font-medium text-[#07074D]"
                    >
                        Categories
                    </label>
                    <select
                        wire:model="product.category_id"
                        name="category_id"
                        id="category_id"
                        class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                    >
                        <option>Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->categories_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="my-2">
                <label
                    for="category_id"
                    class="mb-2 block text-base font-medium text-[#07074D]"
                >
                    Meal type
                </label>
                <select
                    wire:model="product.meal_type"
                    name="meal_type"
                    id="meal_type"
                    class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                >
                    <option>Select meal type</option>
                    @foreach($meal_types as $key => $type)
                        <option value="{{ $key }}">{{ $type }}</option>
                    @endforeach
                </select>
            </div>

            <div class="my-4">
                <div class="flex items-center border border-yellow-500 rounded-lg">
                    <div class="w-1/6 flex justify-center py-1 bg-yellow-500">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                        </svg>
                    </div>
                    <label class="w-5/6 px-4 py-1">
                        @if(!isset($product['upload_picture']))
                            Select a file
                        @else
                            {{ \Illuminate\Support\Str::limit($product['upload_picture']->getClientOriginalName(), 30) }}
                        @endif
                        <input wire:model="product.upload_picture" type='file' name="upload_picture" class="hidden" />
                    </label>
                    @if(isset($product['upload_picture']))
                    <button wire:click="remove_uploaded_file" type="button" class="px-4 py-1 mr-2 hover:bg-yellow-500 cursor-pointer rounded-md">
                        x
                    </button>
                    @endif
                </div>
            </div>
            <div class="mt-4 text-right">
                <button type="submit" class="text-white text-md bg-yellow-500 px-4 py-2 rounded-md">{{ $is_edit ? 'Update' :'Add' }} Product</button>
            </div>
        </form>
    </div>
</div>
