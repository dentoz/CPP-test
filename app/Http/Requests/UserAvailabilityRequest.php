<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserAvailabilityRequest extends FormRequest
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
            'nickname' => 'required|string',
            'google_id' => 'required|string',
            'fcmToken' => 'required|string'
        ];
    }

    /**
     * Customize the error messages for validation.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'nickname.required' => 'The nickname field is required.',
            'nickname.string' => 'The nickname must be a valid string.',
            'google id.required' => 'The google id field is required.',
            'google id.string' => 'The google id must be a valid string.',
        ];
    }
}