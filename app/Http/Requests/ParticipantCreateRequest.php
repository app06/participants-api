<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticipantCreateRequest extends FormRequest
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
            'email' => 'required|email|string|max:255|unique:participants,email',
            'first_name' => 'string|max:255|nullable',
            'last_name' => 'string|max:255|nullable',
            'event_id' => 'required|exists:events,id',
        ];
    }
}
