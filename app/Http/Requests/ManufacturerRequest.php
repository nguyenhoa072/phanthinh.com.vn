<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ManufacturerRequest extends Request
{
    public function __construct()
    {

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
     * @return array
     */
    public function rules()
    {
        $data = array();
        $data['name'] = 'required';
        return $data;
    }

    public function messages()
    {
        $data = array();
        $data["name.required"] = 'Vui lòng nhập tên!';
        return $data;
    }
}