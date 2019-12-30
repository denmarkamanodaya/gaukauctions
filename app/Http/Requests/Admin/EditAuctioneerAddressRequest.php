<?php namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;
use App\Rules\Phone;

/**
 * Class EditAuctioneerRequest
 * @package App\Http\Requests\Admin
 */
class EditAuctioneerAddressRequest extends Request {

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
			'address' => 'required_without:online_only|nullable|string',
            'town' => 'required_without:online_only|nullable|AlphaSpaces',
            'county' => 'required_without:online_only|nullable|AlphaSpaces',
            'postcode' => 'required_without:online_only|nullable|AlphaSpaces',
            'country_id' => 'required|numeric',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
			'phone' => 'nullable|string',
			'auction_url' => 'nullable|url',
        ];
	}

}
