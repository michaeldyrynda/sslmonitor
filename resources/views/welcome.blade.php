<x-layout>
    <x-slot name="body">
        <div class="min-h-screen bg-gray-50 flex flex-col gap-y-8 justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full sm:max-w-md">
                <livewire:add-site />
            </div>
            <div class="sm:mx-auto sm:w-full sm:max-w-7xl">
                <livewire:site-monitors />
            </div>
        </div>
    </x-slot>
</x-layout>
