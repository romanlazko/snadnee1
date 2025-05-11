<?php

namespace App\Rules;

use App\Models\Table;
use Illuminate\Contracts\Validation\Rule;

class Available implements Rule
{
    public function passes($attribute, $value): bool
    {
        if (Table::isAvailable($value)->get()->isEmpty()) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return __('The table is not available for this date');
    }
}
