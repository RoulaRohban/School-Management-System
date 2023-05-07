<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:100',
            'father_name' => 'required|string|min:3|max:100',
            'mother_name' => 'required|string|min:3|max:100',
            'birthdate' => 'required|date',
            'origin_place' => 'required|string|min:3|max:100',
            'school_id' => 'nullable|exists:schools,id',
            'identificationPath' => 'required|image',
            'familyBookPath' => 'required|image',
            'subject_name' => 'required|string|min:3|max:100'
        ];
    }
}
