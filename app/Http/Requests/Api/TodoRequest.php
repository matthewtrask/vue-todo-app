<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
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

    public function getTodo() : string
    {
        return $this->get('todo');
    }

    public function getNotes() : string
    {
        return $this->get('notes');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'todo'     => 'required|string',
            'notes'    => 'required|string',
        ];
    }
}
