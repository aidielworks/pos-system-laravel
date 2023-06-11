<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        {{ __('Order') }}
        </h2>
    </x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="min-h-[75vh] overflow-hidden sm:rounded-lg">
            <livewire:order.order-list/>
        </div>
    </div>
</div>
</x-app-layout>
