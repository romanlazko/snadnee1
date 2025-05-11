<x-app-layout>
    <x-slot name="header">
        <div class="md:flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Users') }}
            </h2>
    
            <a href="{{ route('admin.user.create') }}">
                <x-primary-button>
                    <x-heroicon-s-plus-circle 
                        class="size-5 mr-2"
                    />
                    {{ __('Create user') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-table.wrapper>
        <x-slot name="thead">
            <tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>NAME</x-table.th>
                <x-table.th>EMAIL</x-table.th>
                <x-table.th>CREATED AT</x-table.th>
                <x-table.th></x-table.th>
            </tr>
        </x-slot>

        @forelse ($users as $user)
            <x-table.row>
                <x-table.td>{{ $user->id }}</x-table.td>
                <x-table.td>{{ $user->name }}</x-table.td>
                <x-table.td>{{ $user->email }}</x-table.td>
                <x-table.td>{{ $user->created_at->format('d.m.Y') }}</x-table.td>
                <x-table.td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                    <a href="{{ route('admin.user.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                        <x-heroicon-o-pencil-square 
                            class="size-5"
                        />
                    </a>
                    
                    <form action="{{ route('admin.user.destroy', $user) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this user?')">
                            <x-heroicon-o-trash 
                                class="size-5"
                            />
                        </button>
                    </form>
                </x-table.td>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    {{ __('No users found') }}
                </x-table.td>
            </x-table.row>
        @endforelse
    </x-table.wrapper>
    
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-app-layout>