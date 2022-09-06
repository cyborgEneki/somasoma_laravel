<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BookRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data'    => $validator->errors(),
        ]));
    }

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
            'name' => 'required',
            'author' => 'required',
            'book_jacket' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ] +
            ($this->route('id') ? $this->update() : $this->store());
    }

    protected function store()
    {
        return [
            'book' => 'required|mimes:pdf|max:100000'
        ];
    }

    protected function update()
    {
        return [
            'book' => 'nullable|mimes:pdf|max:100000'
        ];
    }
}
