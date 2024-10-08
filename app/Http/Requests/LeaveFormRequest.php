<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'leave_type_id' => 'required',
            'description'   => 'required',
            'start_date'    => [
                'required',
                'date',
                'before_or_equal:end_date',
            ],
            'end_date'      => [
                'required',
                'date',
            ],
        ];
    }
}
