<?php

namespace App\Http\Requests;

use App\Exceptions\ReseravtionNotAuthorizedException;
use App\Exceptions\ReservationNotFoundException;
use App\Exceptions\TableNotAuthorizedException;
use App\Models\Reservation;
use App\Models\Table;
use App\Traits\HasFailedValidationResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    use HasFailedValidationResponse;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $isAuthorized = true;

        $reservation = (new Reservation)->findOrFail(request()->reservation_id);

        if(!$reservation) {
            throw new ReservationNotFoundException();
        }

        $isAuthorized = Gate::allows('own', $reservation);

        if ($reservation->table_id != request()->table_id) {
            throw new TableNotAuthorizedException();
        }

        return $isAuthorized;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'table_id' => 'required|exists:tables,id',
            'reservation_id' => 'required|exists:reservations,id',
            'meals' => 'required|array'
        ];
    }
}
