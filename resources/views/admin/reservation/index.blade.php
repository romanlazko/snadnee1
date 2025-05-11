<x-app-layout>
    <x-slot name="header">
        <div class="md:flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Reservations') }}
            </h2>
    
            <a href="{{ route('admin.reservation.create') }}">
                <x-primary-button>
                    <x-heroicon-s-plus-circle 
                        class="size-5 mr-2"
                    />
                    {{ __('Create reservation') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <livewire:admin.reservation-list />
</x-app-layout>