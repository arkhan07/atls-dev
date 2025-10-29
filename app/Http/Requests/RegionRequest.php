<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $regionId = $this->route('region') ? $this->route('region')->id : null;

        return [
            'name' => 'required|string|max:255',
            'whatsapp' => [
                'required',
                'regex:/^628[0-9]{8,13}$/',
                'string',
                'max:15'
            ],
            'order_num' => 'required|integer|min:0',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'icon_image' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,gif|max:2048' : 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'banner_image' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,gif|max:2048' : 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'content' => 'nullable|string',
            'is_active' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama region harus diisi.',
            'whatsapp.required' => 'Nomor WhatsApp harus diisi.',
            'whatsapp.regex' => 'Format WhatsApp tidak valid. Harus diawali dengan 628.',
            'icon_image.required' => 'Ikon region harus diupload.',
            'icon_image.image' => 'File ikon harus berupa gambar.',
            'icon_image.mimes' => 'Format ikon harus JPG, PNG, atau GIF.',
            'icon_image.max' => 'Ukuran ikon maksimal 2MB.',
            'banner_image.required' => 'Banner region harus diupload.',
            'banner_image.image' => 'File banner harus berupa gambar.',
            'banner_image.mimes' => 'Format banner harus JPG, PNG, atau GIF.',
            'banner_image.max' => 'Ukuran banner maksimal 2MB.',
        ];
    }

    protected function prepareForValidation()
    {
        // Clean WhatsApp number
        if ($this->has('whatsapp')) {
            $this->merge([
                'whatsapp' => preg_replace('/[^0-9]/', '', $this->whatsapp)
            ]);
        }

        // Set default meta_title if empty
        if ($this->has('name') && empty($this->meta_title)) {
            $this->merge([
                'meta_title' => $this->name
            ]);
        }

        // Set is_active
        $this->merge([
            'is_active' => $this->has('is_active') ? true : false
        ]);
    }
}