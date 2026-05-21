<?php

namespace Modules\Project\Http\Requests\SMTP;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'type' => ['required'],

            'host' => ['required'],

            'port' => ['required'],

            'username' => ['required'],

            'password' => ['required'],

            'from_email' => ['required', 'email'],

            'from_name' => ['required'],

        ];
    }
}
