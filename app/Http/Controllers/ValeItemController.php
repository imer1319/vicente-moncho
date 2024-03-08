<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ValeItemController extends Controller
{
    public function validateForm(Request $request)
    {
        $validatedData = $request->validate([
            'descripcion' => 'required',
            'cantidad' => 'required|numeric',
            'importe' => 'nullable',
        ],
        [
            'descripcion.required' => 'El campo descripcion es obligatorio',
            'cantidad.required' => 'El campo cantidad es obligatorio',
            'cantidad.numeric' => 'El campo cantidad debe ser numerico',
        ]
    );
        return response()->json(['success' => true, 'data' => $validatedData, 'message' => 'Datos validados correctamente']);
    }
}
