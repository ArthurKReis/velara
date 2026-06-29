<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            'demons' => 'required|array|max:5',
            'demons.*' => 'exists:demons,id',
        ];
    }

    public function messages()
    {
        return [
            'demons.max' => 'Um time pode ter no máximo 5 demônios.',
            'demons.required' => 'Selecione pelo menos 1 demônio para o time.',
        ];
    }
}
