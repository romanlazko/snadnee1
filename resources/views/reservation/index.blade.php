<x-app-layout>
    <x-slot name="header">
        <div class="md:flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Reservations') }}
            </h2>
    
            <a href="{{ route('user.reservation.create') }}">
                <x-primary-button>
                    <x-heroicon-s-plus-circle 
                        class="size-5 mr-2"
                    />
                    {{ __('Create reservation') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <x-table.wrapper>
        <x-slot name="thead">
            <tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>USER</x-table.th>
                <x-table.th>TABLE</x-table.th>
                <x-table.th>DATE</x-table.th>
                <x-table.th>TIME</x-table.th>
                <x-table.th>PERSONS</x-table.th>
                <x-table.th>COMMENT</x-table.th>
                <x-table.th>CREATED AT</x-table.th>
                <x-table.th></x-table.th>
            </tr>
        </x-slot>

        @forelse ($reservations as $reservation)
            <x-table.row>
                <x-table.td>{{ $reservation->id }}</x-table.td>
                <x-table.td>{{ $reservation->user->name }}</x-table.td>
                <x-table.td>{{ $reservation->table->name ?? '—' }}</x-table.td>
                <x-table.td>{{ $reservation->date->format('d.m.Y') }}</x-table.td>
                <x-table.td>{{ $reservation->time }}</x-table.td>
                <x-table.td>{{ $reservation->number_of_people }}</x-table.td>
                <x-table.td>{{ $reservation->comment ?? '—' }}</x-table.td>
                <x-table.td>{{ $reservation->created_at->format('d.m.Y') }}</x-table.td>
                <x-table.td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                    <form action="{{ route('user.reservation.destroy', $reservation) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this reservation?')">
                            <x-heroicon-o-trash 
                                class="size-5"
                            />
                        </button>
                    </form>
                </x-table.td>
            </x-table.row>
        @empty
            <x-table.row>
                <x-table.td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    {{ __('No reservations found') }}
                </x-table.td>
            </x-table.row>
        @endforelse
    </x-table.wrapper>

    <div class="mt-6">
        {{ $reservations->links() }}
    </div>
</x-app-layout>
