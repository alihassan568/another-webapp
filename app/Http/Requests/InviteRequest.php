<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:invites,email',
            'role_id' => [
                'required',
                'exists:roles,id',
                function ($attribute, $value, $fail) {
                    $role = \App\Models\Role::find($value);
                    if ($role && $role->name === 'Super Admin') {
                        $fail('Super Admin role cannot be assigned through invitations.');
                    }
                },
            ],
        ];
    }
}

