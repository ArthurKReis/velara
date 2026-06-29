<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'demons' => 'nullable|array|max:5',
            'demons.*' => 'exists:demons,id',
        ];
    }

    public function messages()
    {
        return [
            'demons.max' => 'Um time pode ter no máximo 5 demônios.',
        ];
    }
}
?>