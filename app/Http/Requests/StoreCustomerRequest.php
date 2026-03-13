<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $customerId = $this->route('customer') ? $this->route('customer')->id : null;

        return [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|unique:customers,phone,' . $customerId,
            'email' => 'nullable|email|unique:customers,email,' . $customerId,
            'type' => 'required|in:retail,wholesale,vip',
            'address' => 'nullable|string',
            'aroma_preferences' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ];
    }
}
