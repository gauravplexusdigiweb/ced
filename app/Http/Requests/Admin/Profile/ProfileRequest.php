<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProfileRequest extends FormRequest
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
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'mobile' => 'required',
        ];

        if(Auth::user()->user_type == 'Candidate'){
            $rules['first_name'] = 'required';
            $rules['last_name'] = 'required';
        }else{
            $rules['name'] = 'required';
        }

        return $rules;
    }
}
