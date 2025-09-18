<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('update', $this->post);;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:200',
            'content' => 'required|string|min:3|max:100000',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image'
        ];
    }
}
