<?php

namespace App\Http\Requests\Mark;

use Illuminate\Foundation\Http\FormRequest;

class StoreMarkRequest extends FormRequest
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
            'marks' => 'required|array',
            'marks.*.mark' => 'required|numeric|max:100',
            'marks.*.student_id'  => 'required|exists:students,id',
            'subject_id' => 'nullable'
        ];
    }
}
