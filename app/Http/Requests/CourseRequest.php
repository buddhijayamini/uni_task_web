<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'seo_url' => [
                'required',
                'string',
                'max:255',
                Rule::unique('courses')->ignore($this->id), // Adjust this if the route parameter is named differently
            ],
            'faculty_id' => 'required|exists:faculties,id',
            'category_id' => 'required|exists:departments,id',
            'status' => 'required|in:draft,publish',
            'published_at'=> 'nullable'
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The course name is required.',
            'seo_url.required' => 'The SEO URL is required.',
            'faculty.required' => 'The faculty is required.',
            'category.required' => 'The category is required.',
            'status.required' => 'The status is required.',
            'seo_url.unique' => 'The SEO URL must be unique.',
            'status.in' => 'The selected status is invalid.',
        ];
    }
}
