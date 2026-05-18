<?php

namespace Modules\Project\Http\Requests\GeoFilter;

use Illuminate\Foundation\Http\FormRequest;

class StoreGeoFilterRequest extends FormRequest
{
    /**
     * Authorize request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [

            'latitude_range' => [

                'required',

                'numeric',

                'min:0',

                'max:1'

            ],

            'longitude_range' => [

                'required',

                'numeric',

                'min:0',

                'max:1'

            ],

        ];
    }

    /**
     * Custom messages
     */
    public function messages(): array
    {
        return [

            'latitude_range.required' =>
                'Latitude range is required',

            'longitude_range.required' =>
                'Longitude range is required',

        ];
    }
}
