<?php

namespace App\Http\Requests;

use App\Rules\AfterDate;
use App\Traits\HasFailedValidationResponse;
use Illuminate\Foundation\Http\FormRequest;

class CheckAvailabiltyRequest extends FormRequest
{
    use HasFailedValidationResponse;

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
            'date' => 'required|date|date_format:Y-m-d|after_or_equal:'.date('Y-m-d'),
            'from' => ['required', 'date_format:h:i a', new AfterDate($this->input('date'))],
            'to' => 'required|date_format:h:i a|after:from',
            'guests_count' => 'required|integer|min:1',
        ];
    }
}
