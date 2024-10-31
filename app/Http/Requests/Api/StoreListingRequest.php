<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreListingRequest extends FormRequest
{
    public function authorize()
    {

        return auth()->user() && auth()->user()->is_admin;
    }

    protected function failedAuthorization()
    {
        throw new \Illuminate\Auth\Access\AuthorizationException('Only admins are allowed to create listings.');
    }

    protected function prepareForValidation()
    {
        if ($this->has('expiry_date')) {
            $this->merge([
                'expiry_date' => date('Y-m-d H:i:s', strtotime($this->expiry_date))
            ]);
        }
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'base_price' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'status' => ['nullable', Rule::in(['active', 'sold'])],
            'expiry_date' => 'required|date|after:today',
        ];
    }

    public function messages()
    {
        return [
            'base_price.min' => 'The base price must be a positive number.',
            'expiry_date.after' => 'The expiry date must be a future date.',
        ];
    }
}
