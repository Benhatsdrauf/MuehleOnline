<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoneMoveRequest extends JsonRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "old_position" => "bail|required",
            "new_position" => "bail|required"
        ];
    }
}
