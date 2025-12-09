<?php

namespace App\Rules;

use App\Models\Permission;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class HasValidPermissions implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        foreach ($value as $permission) {
            if (! isset($permission['id']) || ! isset($permission['name'])) {
                $fail(':attribute structure is not correct');
            }

            if (! Permission::where('id', $permission['id'])->exists()) {
                $fail('"' . $permission['name'] . '" permission does not exist');
            }
        }
    }
}
