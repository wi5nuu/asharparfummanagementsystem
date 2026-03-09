<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
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
        return [
            'customer_id'          => 'nullable|exists:customers,id',
            'customer_type'        => 'required|in:retail,wholesale',
            'items'                => 'required|array',
            'items.*.product_id'   => 'required|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.price'        => 'required|numeric|min:0',
            'items.*.bonus_quantity' => 'nullable|integer|min:0',
            'items.*.bonus_note'   => 'nullable|string|max:255',
            'discount_amount'      => 'nullable|numeric|min:0',
            'payment_method'       => 'required|in:cash,qris,transfer,debit_card,credit_card',
            'paid_amount'          => 'required|numeric|min:0',
            'notes'                => 'nullable|string',
            'coupon_code'          => 'nullable|exists:coupons,code'
        ];
    }
}
