<div>
    <div x-data="{open: false}">
        <div class="mb-3">
            <a @click.prevent="open = !open" href="#" class="px-4 py-2 bg-yellow-500 text-white rounded">Add Table</a>
        </div>
        <div class="relative overflow-hidden transition-all max-h-0 duration-300" x-ref="container1" x-bind:style="open ? 'max-height: ' + $refs.container1.scrollHeight + 'px' : ''">
            <div class="w-1/3">
                <form method="POST" action="{{ route('company.add-table') }}">
                    @csrf
                    <div class="my-4">
                        <label for="table_no" class="mb-2 block text-base font-medium">
                            Table No
                        </label>
                        <input
                            type="text"
                            name="table_no"
                            class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                        />
                    </div>
                    <div class="my-4">
                        <label for="status" class="mb-2 block text-base font-medium">
                            Status
                        </label>
                        <select name="status" id="" class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                            @foreach(App\Enum\TableStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ $status->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="px-4 py-2 bg-green-500 text-white rounded">Add</button>
                </form>
            </div>
        </div>
    </div>

    <div class="mt-3 p-4 grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7">
        @foreach ($tables as $table)
            <a wire:click.prevent="$emit('openModal', 'create-edit-table-modal', {{ json_encode(['table' => $table->id]) }}) href="#">
                <div class="relative w-32 h-32 border rounded-full flex flex-col justify-center items-center text-black">
                    <p class="text-3xl">{{ $table->table_no }}</p>
                    <p
                        @class([
                            'px-3 py-1 text-xs rounded-full border cursor-pointer text-center text-white',
                            'bg-green-500' => $table->status == App\Enum\TableStatus::AVAILABLE,
                            'bg-red-500' => $table->status == App\Enum\TableStatus::NOT_AVAILABLE,
                            'bg-yellow-500' => $table->status == App\Enum\TableStatus::RESERVED,
                        ])
                    >{{ $table->status->getLabel() }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div>
        {{ $tables->links() }}
    </div>
</div>
