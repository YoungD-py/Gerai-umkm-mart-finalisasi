<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $categoryId = $this->route('category')->id ?? $this->input('id');
        
        return [
            'id' => 'required',
            'nama' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($categoryId) {
                    $exists = Category::whereRaw('LOWER(nama) = ?', [strtolower($value)])
                        ->where('id', '!=', $categoryId)
                        ->exists();
                    if ($exists) {
                        $fail('Nama mitra binaan sudah ada (tidak boleh sama meskipun berbeda huruf besar/kecil).');
                    }
                },
            ],
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
            'nama.required' => 'Nama mitra binaan wajib diisi.',
            'nama.string' => 'Nama mitra binaan harus berupa teks.',
            'nama.max' => 'Nama mitra binaan tidak boleh lebih dari 255 karakter.',
            'id.required' => 'ID mitra binaan diperlukan.',
        ];
    }
}
