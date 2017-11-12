<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RobotRequest extends FormRequest
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
            'x' => 'Required | Integer | Min:0',
            'y' => 'Required | Integer | Min:0',            
            'heading' => 'Required | Max:1',
            'commands' => 'Required'                
        ];
    }
}
