<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProveedoreRequest extends FormRequest
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
        $proveedore = $this->route('proveedore');
        return [
            //
            'razon_social' => 'required|max:80',
            'direccion' => 'required|max:80',

            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => 'required|max:20|unique:personas,numero_documento,' . $proveedore->persona->id
        ];
    }
}
