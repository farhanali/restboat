<?php namespace Restboat\Http\Requests;

class UserPreferenceRequest extends Request {

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
        $skipId = \Auth::user()->preferences->id;

		return array(
			'user_identifier'   => 'required|unique:user_preferences,user_identifier,' . $skipId . '|min:5|alpha_dash',
            'timezone'          => 'required|timezone',
            'email'             => 'sometimes|email',

            'default_response_status'   => 'required|numeric',
            'default_response_type'     => 'required|string',
            'default_response_content'  => 'required|string',
        );
	}

}
