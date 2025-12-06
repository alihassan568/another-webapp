<?php

namespace App\Http\Requests\Role;

use App\Modules\User\Enums\RoleType;
use App\Rules\HasValidPermissions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roleId = $this->route('role');

        return [
            'name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('roles', 'name')->ignore($roleId),
            ],
            'type' => [
                'required',
                'string',
                Rule::in([RoleType::INTERNAL->value, RoleType::EXTERNAL->value]),
            ],
            'permissions' => ['required', 'array', new HasValidPermissions],
        ];
    }
}

