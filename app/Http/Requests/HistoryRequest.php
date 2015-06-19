<?php namespace Restboat\Http\Requests;

class HistoryRequest extends Request {

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
		return array(
			'path'          => 'required|min:3',
            'method'        => 'required|string|min:3',
            'content_type'  => 'string',
            'data'          => 'string',
        );
	}

}
