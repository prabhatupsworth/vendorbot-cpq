<?php

namespace Modules\Project\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => 'required|exists:projects,id',

            'company_name' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',

            'address_line1' => 'nullable|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',

            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Project is required',
            'project_id.exists' => 'Invalid project selected',
            'email.email' => 'Please enter a valid email address',
            'logo.image' => 'Logo must be an image file',
        ];
    }
}
