<?php

namespace App\Rules;

use App\Models\Table;
use Illuminate\Contracts\Validation\Rule;

class AvailableSeats implements Rule
{
    protected ?Table $table;

    public function __construct(int|string|null $tableId)
    {
        $this->table = Table::find($tableId);
    }

    public function passes($attribute, $value): bool
    {
        if (! $this->table) {
            return false;
        }

        return $value <= $this->table?->seat_count;
    }

    public function message(): string
    {
        return __('The :attribute must be less than or equal to '. $this->table?->seat_count);
    }
}
