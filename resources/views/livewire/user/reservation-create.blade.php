<form wire:submit.prevent="create" class="space-y-6">
    <x-section>
        <div>
            <x-input-label for="date" :value="__('Date')" />
            
            <x-text-input id="date" min="{{ now()->toDateString() }}" class="block mt-1 w-full" type="date" name="date" wire:model.live="form.date" required autofocus />
    
            <x-input-error :messages="$errors->get('form.date')" class="mt-2" />
        </div>
        
        <div>
            <x-input-label for="time" :value="__('Time')" /> 
            
            <x-select-input id="time" class="block mt-1 w-full" name="time" wire:model.live.debounce.500ms="form.time" required :disabled="$errors->has('form.date')">
                <option value="">{{ __('Select time') }}</option>
                @for ($i = 12; $i <= 24; $i++)
                    <option value="{{ $i }}:00">{{ $i }}:00</option>
                @endfor
            </x-select-input>
    
            <x-input-error :messages="$errors->get('form.time')" class="mt-2" />
        </div>
    </x-section>

    <x-section>
        <div>
            <x-input-label for="table" :value="__('Table')" /> 
        
            <x-select-input id="table" class="block mt-1 w-full" name="table" wire:model.live="form.table" required :disabled="$errors->has('form.date')">
                <option value="">{{ __('Select table') }}</option>
                @foreach ($tables as $table)
                    <option value="{{ $table->id }}">{{ "{$table->name} ({$table->seat_count})" }}</option>
                @endforeach
            </x-select-input>

            <x-input-error :messages="$errors->get('form.table')" class="mt-2" />
        </div>
    
        <div>
            <x-input-label for="number_of_people" :value="__('Number of people')" />
            
            <x-text-input id="number_of_people" class="block mt-1 w-full" type="number" name="number_of_people" wire:model.live="form.number_of_people" required :disabled="$errors->has('form.date')"/>

            <x-input-error :messages="$errors->get('form.number_of_people')" class="mt-2" />
        </div>
    </x-section>

    <x-section>
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" wire:model.live.debounce.500ms="form.phone" required :disabled="$errors->has('form.date')"/>

            <x-input-error :messages="$errors->get('form.phone')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="comment" :value="__('Comment')" />
            
            <x-text-input id="comment" class="block mt-1 w-full" type="text" name="comment" wire:model="form.comment" :disabled="$errors->has('form.date')"/>

            <x-input-error :messages="$errors->get('form.comment')" class="mt-2" />
        </div>
    </x-section>
    
    <div class="flex items-center justify-end mt-6">
        <x-primary-button class="ml-4" type="submit" :disabled="$errors->has('form.date')">
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>

