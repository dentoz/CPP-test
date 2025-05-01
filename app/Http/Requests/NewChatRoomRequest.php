<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewChatRoomRequest extends FormRequest
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
            'google_id' => 'required|string',
            'invite' => 'required|email:rfc,dns',
            'nickname' => 'required|string',
            'topic' => 'required|string'
        ];
    }
}