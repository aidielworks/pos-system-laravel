<x-guest-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-yellow-900 leading-tight">
        {{ __('Self Order') }}
        </h2>
    </x-slot>

    @if(request()->has('order'))
        <div class="h-[calc(100vh-144px)] p-4">
            <livewire:pos.pos :selected_table_id="$table->id" :self_session_key="$session_key" :company_id="$company_id"/>
        </div>
    @endif

    @if(request()->has('success'))
        <div class="min-h-[75vh]">
            <div class="pt-6 pb-12 flex flex-col items-center gap-2">
                @if($company->logo_url)
                    <img class="object-scale-down h-28 w-28" src="{{ asset($company->logo_url) }}" alt="{{ $company->name . 'logo' }}">
                @endif
                <p class="text-2xl font-bold">{{ $company->name }}</p>
            </div>

            <div class="flex flex-col gap-4 items-center">
                <div class="text-green-600 w-48 h-48">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                </div>
                <div class="flex flex-col items-center gap-2">
                    <p class="text-4xl font-bold">Thank you for ordering!</p>
                    <p class="text-xl text-gray-400">Enjoy your meal!</p>
                </div>
                <div class="mt-4 flex gap-4">
                    <a href="#" class="px-3 py-2 text-xl text-gray-400 rounded-lg border border-gray-400 hover:bg-gray-400 hover:text-white">View Order</a>
                    <a href="{{ $table->url }}" class="px-3 py-2 text-xl text-white rounded-lg bg-yellow-500 hover:bg-white hover:border hover:border-yellow-500 hover:text-yellow-500">New Order</a>
                </div>
            </div>
        </div>
    @endif
</x-guest-layout>
