<?php

namespace Modules\Pipedrive\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePipedriveRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules
     */
    public function rules(): array
    {
        return [

            'account_name' => [

                'required',

                'string',

                'max:255',

            ],

            /*
            |--------------------------------------------------------------------------
            | Optional on Update
            |--------------------------------------------------------------------------
            */

            'api_key' => [

                'nullable',

                'string',

                'min:10',

            ],

            'base_url' => [

                'required',

                'url',

            ],

        ];
    }

    /**
     * Messages
     */
    public function messages(): array
    {
        return [

            'account_name.required' => 'Account name is required',

            'base_url.required' => 'Base URL is required',

            'base_url.url' => 'Enter valid URL',

        ];
    }
}
