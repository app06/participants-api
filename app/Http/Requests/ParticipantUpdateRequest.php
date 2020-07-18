<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantUpdateRequest extends FormRequest
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
            'email' => 'email|string|max:255|nullable',
            'first_name' => 'string|max:255|nullable',
            'last_name' => 'string|max:255|nullable',
            'event_id' => 'exists:events,id',
        ];
    }
}
