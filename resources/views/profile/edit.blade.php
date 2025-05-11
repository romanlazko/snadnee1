<x-app-layout>
    <x-slot name="header">
        <div class="md:flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Profile') }}
            </h2>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-primary-button>
                    <x-heroicon-o-arrow-right-end-on-rectangle
                        class="size-5 mr-2"
                    />
                    {{ __('Log Out') }}
                </x-primary-button>
            </form>
        </div>
    </x-slot>

    <div class="space-y-6">
        <x-section>
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-section>

        <x-section>
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </x-section>

        <x-section>
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </x-section>
    </div>
</x-app-layout>
