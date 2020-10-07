<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramKerjaRequest extends FormRequest
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
            'tahun' => 'required|numeric',
            'bulan' => 'required|numeric|min:1|max:12',
            'target' => 'required|numeric',
            'jenis_sarana_id' => 'required|numeric',
            'jenis_pekerjaan_id' => 'required|numeric',
        ];
    }
}
