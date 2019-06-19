<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users')->ignore($this->route('user'))
            ],
        ];
    }

    protected function withValidator($validator)
    {
        $validator->sometimes('password', 'required|string|min:8|confirmed', function () {
            return ! empty($this->input('password'))
                || ! empty($this->input('password_confirmation'));
        });
    }
}
