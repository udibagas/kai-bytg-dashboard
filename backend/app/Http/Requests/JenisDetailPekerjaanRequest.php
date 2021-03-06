<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisDetailPekerjaanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'urutan' => 'required|numeric',
            'nama' => 'required|max:255',
            'keterangan' => 'max:255',
            'bobot' => 'required|numeric'
        ];
    }
}
