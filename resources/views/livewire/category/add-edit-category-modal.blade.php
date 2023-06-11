<div>
    <style>
        /* CHECKBOX TOGGLE SWITCH */
        /* @apply rules for documentation, these do not work as inline style */
        .toggle-checkbox:checked {
            @apply: right-0 border-green-400;
            right: 0;
            border-color: #68D391;
        }
        .toggle-checkbox:checked + .toggle-label {
            @apply: bg-green-400;
            background-color: #68D391;
        }
    </style>
    <div class="p-4">
        <div class="flex justify-between items-center border-b border-yellow-500">
            <p class="text-lg font-bold">
                {{ $is_edit ? 'Update' :'Create' }} Category
            </p>
            <button class="mb-2 bg-gray-200 py-1 px-2 rounded-md font-sm hover:bg-gray-300" wire:click="$emit('closeModal')">X</button>
        </div>
        <form method="post" action="{{ $is_edit ? route('category.update', $category['id']) : route('category.store') }}" enctype="multipart/form-data">
            @csrf
            @if($is_edit) @method('patch') @endif
            <div class="p-4">
                <div class="my-4">
                    <div class="flex items-center">
                        <img class="mr-4 h-20 w-20 rounded-lg object-cover object-center"
                             alt="Image placeholder"
                             src="{{ isset($category['picture_url']) ? asset($category['picture_url']) : asset('asset/img/default-image.png') }}">
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-2">
                                <label
                                    for="categories_name"
                                    class="block text-base font-medium text-[#07074D]"
                                >
                                    Categories Name
                                </label>
                                @if($is_edit)
                                    <div>
                                        <div class="relative inline-block w-10 mr-2 align-middle select-none transition duration-200 ease-in">
                                            <input wire:click="toggleStatus({{ $category['id'] }})"
                                                   type="checkbox"
                                                   name="category_status"
                                                   id="toggle"
                                                   class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer"
                                                   @if ($category['status']) checked @endif
                                            />
                                            <label for="toggle" class="toggle-label block overflow-hidden h-6 rounded-full bg-gray-300 cursor-pointer"></label>
                                        </div>
                                        <label for="toggle" class="text-xs text-gray-700">Status</label>
                                    </div>
                                @endif
                            </div>
                            <input
                                wire:model="category.categories_name"
                                type="text"
                                name="categories_name"
                                id="categories_name"
                                class="w-full rounded-md border border-[#e0e0e0] bg-white py-3 px-6 text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="text-white text-md bg-yellow-500 px-4 py-2 rounded-md">{{ $is_edit ? 'Update' :'Add' }} Category</button>
            </div>
        </form>
    </div>
    <script>
        window.addEventListener('success', function(e) {
            Swal.fire({
                title: e.detail?.message ?? '',
                icon: 'success',
                showConfirmButton: false,
                timer: 1000,
            })
        })
    </script>
</div>


