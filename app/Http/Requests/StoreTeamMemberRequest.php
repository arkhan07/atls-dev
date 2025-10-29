<?php
// app/Http/Requests/StoreTeamMemberRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order_num' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social.facebook' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.instagram' => 'nullable|url',
            'social.linkedin' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Member name is required',
            'position.required' => 'Position is required',
            'order_num.required' => 'Order number is required',
            'order_num.integer' => 'Order number must be a number',
            'image.required' => 'Member photo is required',
            'image.image' => 'File must be an image',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif',
            'image.max' => 'Image size cannot exceed 2MB',
            'social.*.url' => 'Social media links must be valid URLs',
        ];
    }
}

<?php
// app/Http/Requests/UpdateTeamMemberRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamMemberRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'order_num' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'social.facebook' => 'nullable|url',
            'social.twitter' => 'nullable|url',
            'social.instagram' => 'nullable|url',
            'social.linkedin' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Member name is required',
            'position.required' => 'Position is required',
            'order_num.required' => 'Order number is required',
            'order_num.integer' => 'Order number must be a number',
            'image.image' => 'File must be an image',
            'image.mimes' => 'Image must be jpeg, png, jpg, or gif',
            'image.max' => 'Image size cannot exceed 2MB',
            'social.*.url' => 'Social media links must be valid URLs',
        ];
    }
}