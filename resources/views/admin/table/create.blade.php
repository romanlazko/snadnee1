<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create table') }}
        </h2>
    </x-slot>
    
    <form action="{{ route('admin.table.store') }}" method="POST">
        @csrf
        
        <x-section>
            <div>
                <x-input-label for="name" :value="__('Name')" />
                
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />

                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            
            <div>
                <x-input-label for="description" :value="__('Description')" /> 
                
                <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')" />

                <x-input-error :messages="$errors->get('description')" class="mt-2" />
            </div>
            
            <div>
                <x-input-label for="seat_count" :value="__('Seat count')" />
                
                <x-text-input id="seat_count" class="block mt-1 w-full" type="number" name="seat_count" :value="old('seat_count')" required />

                <x-input-error :messages="$errors->get('seat_count')" class="mt-2" />
            </div>
        </x-section>
            
        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ml-4">
                {{ __('Create') }}
            </x-primary-button>
        </div>
    </form>
</x-app-layout>