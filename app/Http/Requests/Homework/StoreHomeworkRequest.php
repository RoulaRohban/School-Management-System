<?php

namespace App\Http\Requests\Homework;

use Illuminate\Foundation\Http\FormRequest;

class StoreHomeworkRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:200',
            'subject_id' => 'nullable|exists:subjects,id',
            'filePath' => 'nullable',
            'totalMark' => 'required|numeric|max:100',
            'expiredDate' => 'required|date'
        ];
    }
}
