<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureRequest extends FormRequest
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
        $segments = $this->segments();
        $id = $segments[3];
        return [
            'name' => 'required|string',
            'slug' => 'required|alpha_dash|unique:dealers_features,slug,'.$id.',slug',
            'icon' => 'nullable|string',
        ];
    }
}
