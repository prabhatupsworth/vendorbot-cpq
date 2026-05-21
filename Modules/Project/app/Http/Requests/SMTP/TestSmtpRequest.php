<?php

namespace Modules\Project\Http\Requests\SMTP;

use Illuminate\Foundation\Http\FormRequest;

class TestSmtpRequest extends FormRequest
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

            'to_email' => [

                'required',

                'email',

                'max:255',

            ],

            'subject' => [

                'required',

                'string',

                'max:255',

            ],

            'message' => [

                'required',

                'string',

            ],

        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [

            'to_email.required' => 'Recipient email is required',

            'to_email.email' => 'Enter a valid email address',

            'subject.required' => 'Subject is required',

            'message.required' => 'Message is required',

        ];
    }
}
