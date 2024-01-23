<div>
    <div class="p-4">
        <form method="POST" action="{{ route('company.update-table', $table->id) }}">
            @csrf
            @method('put')
            <div class="my-4">
                <label for="table_no" class="mb-2 block text-base font-medium">
                    Table No
                </label>
                <input wire:model="table.table_no"
                    type="text"
                    name="table_no"
                    class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md"
                />
            </div>
            <div class="my-4">
                <label for="status" class="mb-2 block text-base font-medium">
                    Status
                </label>
                <select wire:model="table.status" name="status" id="" class="w-full rounded-md border border-[#e0e0e0] bg-white text-base font-medium text-[#6B7280] outline-none focus:border-[#6A64F1] focus:shadow-md">
                    @foreach(App\Enum\TableStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->getLabel() }}</option>
                    @endforeach
                </select>
            </div>
            <button class="px-4 py-2 bg-green-500 text-white rounded">Update</button>
        </form>
    </div>
</div>
