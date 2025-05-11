<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit user') }}
        </h2>
    </x-slot>
    
    <form action="{{ route('admin.user.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <x-section>
            <div>
                <x-input-label for="name" :value="__('Name')" />
                            
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />

                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
                        
            <div>
                <x-input-label for="email" :value="__('Email')" />
                            
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </x-section>
                    
        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ml-4">
                {{ __('Save') }}
            </x-primary-button>
        </div>
    </form>
</x-app-layout>