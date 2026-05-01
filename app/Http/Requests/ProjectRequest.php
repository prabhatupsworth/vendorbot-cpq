<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'website_url' => 'nullable|url',
            'event_name' => 'nullable|string|max:255',
            'flow_type' => 'required|in:simple,full',
            'invoice_enabled' => 'nullable|boolean',
            'pipedrive_account_id' => 'nullable|exists:pipedrive_accounts,id',
            'invoice_account_id' => 'nullable|exists:invoice_accounts,id',
        ];
    }

    // 🔥 Optional: Custom messages
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required',
            'flow_type.in' => 'Invalid flow type selected',
        ];
    }

    // 🔥 Optional: Clean data before validation
    protected function prepareForValidation()
    {
        $this->merge([
            'invoice_enabled' => $this->invoice_enabled ?? 0,
        ]);
    }
}
