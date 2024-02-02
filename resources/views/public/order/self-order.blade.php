<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        {{ __('Self Order') }}
        </h2>
    </x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="min-h-[75vh] overflow-hidden sm:rounded-lg">
            <livewire:pos.pos :selected_table_id="$table->id"/>
        </div>
    </div>
</div>
</x-guest-layout>
