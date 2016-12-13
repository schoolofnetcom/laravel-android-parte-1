<?php

namespace SON\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillPayRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:255',
            'date_due' => 'required|date',
            'value' => 'required|numeric',
            'category_id' => 'required|exists:categories,id'
        ];
        if($this->isMethod('put')){
            $rules['done'] = 'required|boolean';
        }
        return $rules;
    }
}
