<?php

namespace Modules\Project\Http\Requests\SMTP;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

use App\Enums\SmtpType;

class UpdateSmtpRequest extends FormRequest
{
    /**
     * Authorize
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [

            'type' => [

                'required',

                new Enum(SmtpType::class),

            ],

            'host' => [

                'required',

                'string',

                'max:255',

            ],

            'port' => [

                'nullable',

                'integer',

            ],

            'username' => [

                'nullable',

                'string',

                'max:255',

            ],

            'password' => [

                'nullable',

                'string',

                'max:255',

            ],

            'encryption' => [

                'nullable',

                'in:tls,ssl',

            ],

            'from_email' => [

                'required',

                'email',

                'max:255',

            ],

            'from_name' => [

                'required',

                'string',

                'max:255',

            ],

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'type.required' => 'SMTP type is required',

            'host.required' => 'SMTP host is required',

            'from_email.required' => 'From email is required',

            'from_email.email' => 'Enter valid email address',

            'from_name.required' => 'From name is required',

        ];
    }
}
