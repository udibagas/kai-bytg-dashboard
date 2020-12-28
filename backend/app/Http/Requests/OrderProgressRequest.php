<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderProgressRequest extends FormRequest
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
            'order_id' => 'required',
            'prosentase_pekerjaan' => 'required|numeric',
            'status' => 'required',
            'tanggal_keluar' => 'required_if:status,20',
            'posisi' => 'max:255',
            'checklist' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'order_id' => 'Nomor Order',
            'prosentase_pekerjaan' => 'Prosentase pekerjaan',
            'status' => 'Status',
            'posisi' => 'Posisi',
            'checklist' => 'Checklist'
        ];
    }

    public function messages()
    {
        return [
            'tanggal_keluar.required_if' => 'Tanggal keluar harus diisi jika status order Selesai'
        ];
    }
}
