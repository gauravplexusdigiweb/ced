<?php

namespace App\Http\Requests\SuperAdmin\Branch;

use Illuminate\Foundation\Http\FormRequest;

class BranchEditRequest extends FormRequest
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
            'UserName' => 'required|unique:branch,UserName,'.$this->branch,
//            "Password" => 'required',
            "TPID" => 'required',
        ];
    }
}
