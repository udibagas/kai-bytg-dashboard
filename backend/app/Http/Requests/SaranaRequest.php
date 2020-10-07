<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaranaRequest extends FormRequest
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
            'nomor' => 'required|max:255',
            'nomor_lama' => 'max:255',
            'jenis_sarana_id' => 'required',
            'dipo_id' => 'required',
            'keterangan' => 'max:255'
        ];
    }
}
