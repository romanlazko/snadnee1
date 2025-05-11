<div class="space-y-6">
    <x-section>
        <form action="">
            <x-input-label for="date" :value="__('Date')" />
            
            <x-text-input id="date" class="block mt-1 w-full" type="date" wire:model.live="date" />
        </form>        
    </x-section>

    <x-table.wrapper>
        <x-slot name="thead">
            <tr>
                <x-table.th>ID</x-table.th>
                <x-table.th>USER</x-table.th>
                <x-table.th>PHONE</x-table.th>
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
                <x-table.td>{{ $reservation->phone }}</x-table.td>
                <x-table.td>{{ $reservation->table->name}}</x-table.td>
                <x-table.td>{{ $reservation->date->format('d.m.Y') }}</x-table.td>
                <x-table.td>{{ $reservation->time }}</x-table.td>
                <x-table.td>{{ $reservation->number_of_people }}</x-table.td>
                <x-table.td>{{ $reservation->comment ?? '—' }}</x-table.td>
                <x-table.td>{{ $reservation->created_at->format('d.m.Y') }}</x-table.td>
                <x-table.td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex gap-2">
                    <form action="{{ route('admin.reservation.destroy', $reservation) }}" method="POST">
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
                <x-table.td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-center">
                    {{ __('No reservations found') }}
                </x-table.td>
            </x-table.row>
        @endforelse
    </x-table.wrapper>

    {{ $reservations->links() }}
</div>
