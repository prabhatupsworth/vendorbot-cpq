<?php

namespace Modules\Product\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Authorize request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [

            'project_id' => [
                'required',
                'exists:projects,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'selection_type' => [
                'required',
                'in:single,multiple',
            ],

            'sort_order' => [
                'nullable',
                'integer',
            ],

            'is_required' => [
                'nullable',
                'boolean',
            ],

            'has_tabs' => [
                'nullable',
                'boolean',
            ],

            'has_default' => [
                'nullable',
                'boolean',
            ],

            'active' => [
                'nullable',
                'boolean',
            ],

        ];
    }

    /**
     * Custom messages.
     */
    public function messages(): array
    {
        return [

            'project_id.required' => 'Project is required.',

            'project_id.exists' => 'Selected project does not exist.',

            'name.required' => 'Category name is required.',

            'selection_type.required' => 'Selection type is required.',

        ];
    }

    /**
     * Return JSON validation errors.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(

            response()->json([

                'success' => false,

                'message' => 'Validation failed.',

                'errors' => $validator->errors(),

            ], 422)

        );
    }
}
