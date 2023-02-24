<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()->can('update', User::find($this->route('id')));
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }
}
