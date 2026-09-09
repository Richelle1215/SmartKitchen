<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isRegistered();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'category_id' => ['required', 'exists:recipe_categories,id'],
            'prep_time' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'cook_time' => ['nullable', 'integer', 'min:1', 'max:1440'],
            'servings' => ['required', 'integer', 'min:1', 'max:100'],
            'recipe_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'recipe_video' => ['nullable', 'mimes:mp4,mov,avi,mkv', 'max:102400'], // 100MB
            'is_published' => ['boolean'],
            'ingredients' => ['required', 'array', 'min:1'],
            'ingredients.*.name' => ['required', 'string', 'max:255'],
            'ingredients.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'ingredients.*.unit' => ['required', 'string', 'max:50'],
            'instructions' => ['required', 'array', 'min:1'],
            'instructions.*.text' => ['required', 'string', 'min:10', 'max:2000'],
            'instructions.*.image' => ['nullable', 'string'],
            'instructions.*.video' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Recipe title is required.',
            'description.required' => 'Recipe description is required.',
            'description.min' => 'Description must be at least 10 characters.',
            'category_id.required' => 'Please select a recipe category.',
            'servings.required' => 'Number of servings is required.',
            'ingredients.required' => 'At least one ingredient is required.',
            'instructions.required' => 'At least one instruction step is required.',
            'recipe_image.image' => 'The file must be a valid image.',
            'recipe_image.max' => 'Image size cannot exceed 5MB.',
        ];
    }
}
