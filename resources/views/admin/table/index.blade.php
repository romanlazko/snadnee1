<x-app-layout>
    <x-slot name="header">
        <div class="md:flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tables') }}
            </h2>
    
            <a href="{{ route('admin.table.create') }}">
                <x-primary-button>
                    <x-heroicon-s-plus-circle 
                        class="size-5 mr-2"
                    />
                    {{ __('Create table') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-table.wrapper>
        <x-slot name="thead">
            <tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>NAME</x-table.th>
                <x-table.th>DESCRIPTION</x-table.th>
                <x-table.th>SEAT COUNT</x-table.th>
                <x-table.th>CREATED AT</x-table.th>
                <x-table.th></x-table.th>
            </tr>
        </x-slot>

        @forelse ($tables as $table)
            <x-table.row>
                <x-table.td>{{ $table->id }}</x-table.td>
                <x-table.td>{{ $table->name }}</x-table.td>
                <x-table.td>{{ $table->description ?? '—' }}</x-table.td>
                <x-table.td>{{ $table->seat_count ?? '—' }}</x-table.td>
                <x-table.td>{{ $table->created_at->format('d.m.Y') }}</x-table.td>
                <x-table.td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                    <a href="{{ route('admin.table.edit', $table) }}" class="text-indigo-600 hover:text-indigo-900">
                        <x-heroicon-o-pencil-square 
                            class="size-5"
                        />
                    </a>
                    
                    <form action="{{ route('admin.table.destroy', $table) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this table?')">
                            <x-heroicon-o-trash 
                                class="size-5"
                            />
                        </button>
                    </form>
                </x-table.td>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    {{ __('No tables found') }}
                </x-table.td>
            </x-table.row>
        @endforelse
    </x-table.wrapper>
    
    <div class="mt-6">
        {{ $tables->links() }}
    </div>
</x-app-layout>