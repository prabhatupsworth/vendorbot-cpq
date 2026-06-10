<?php

namespace Modules\Project\Http\Requests\Project;

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
            'website_url' => 'required|url',
            'event_name' => 'required|string|max:255',
            'currency_code' => 'required|exists:currencies,code',
            'language_code' => 'required|exists:languages,code',
            'vat_status' => 'required|in:0,1',
            'vat' => [
                'exclude_if:vat_status,0',
                'required_if:vat_status,1',
                'numeric',
                'gt:0',
                'max:100',
            ],
            'flow_type' => 'required|in:simple,full',
            'invoice_enabled' => 'nullable|boolean',
            'pipedrive_account_id' => 'required|exists:pipedrive_accounts,id',
            'pipeline_id' => [
                'nullable',
                'exists:pipedrive_pipelines,id',
                'required_with:pipedrive_account_id',
            ],
            'invoice_account_id' => 'nullable|exists:invoice_accounts,id',
        ];
    }

    // 🔥 Optional: Custom messages
    public function messages(): array
    {
        return [
            'name.required' => 'Project name is required',
            'flow_type.in' => 'Invalid flow type selected',
            'pipeline_id.required_with' =>
            'Please select a CRM Pipeline when a CRM Account is selected.',
            'vat.required_if' => 'VAT percentage is required when VAT is Included.',
            'vat.gt' => 'VAT percentage must be greater than 0.',
            'vat.max' => 'VAT percentage cannot be greater than 100.',
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
