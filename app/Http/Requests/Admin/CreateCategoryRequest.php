<?php
/**
 * Created By: Quantum Scripts
 * URL : http://quantumscripts.co.uk
 * Author: Dave
 *
 * File : AddUserMembershipRequest.php
 **/

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Quantum\base\Traits\RequestResponse;

class CreateCategoryRequest extends FormRequest {

    use RequestResponse;

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
            'name' => 'required|string',
            'slug' => 'required|alpha_dash|unique:mysql.dealer_categories,slug,NULL,id,user_id,null',
            'description' => 'required|string',
            'icon' => 'nullable|string',
        ];
    }

}