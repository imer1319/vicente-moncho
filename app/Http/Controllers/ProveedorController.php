<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    public function index()
    {
        return view('admin.proveedores.index');
    }

    public function apiproveedores()
    {
        return Proveedor::orderBy('created_at','DESC')->get()->map(function ($proveedor) {
            return [
                'id' => $proveedor->id,
                'nombre' => $proveedor->nombre,
                'ap_pat' => $proveedor->ap_pat,
                'ap_mat' => $proveedor->ap_mat,
                'dni' => $proveedor->dni,
                'email' => $proveedor->email,
                'fullName' => $proveedor->fullName 
            ];
        });
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'ap_pat' => 'required',
            'ap_mat' => 'required',
            'dni' => 'required',
            'email' => 'nullable|email|unique:proveedors,email',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'ap_pat.required' => 'El campo apellido paterno es obligatorio.',
            'ap_mat.required' => 'El campo apellido materno es obligatorio.',
            'dni.required' => 'El campo DNI es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El campo email ya está en uso.',
        ]);

        $proveedor = Proveedor::create([
            'nombre' => $request->nombre,
            'ap_pat' => $request->ap_pat,
            'ap_mat' => $request->ap_mat,
            'email' => $request->email,
            'dni' => $request->dni,
        ]);

        return response()->json(['message' => 'Proveedor creado correctamente'], 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required',
            'ap_pat' => 'required',
            'ap_mat' => 'required',
            'dni' => 'required',
            'email' => [
                'nullable',
                'email',
                Rule::unique('proveedors')->ignore($id),
            ],
        ],[
            'nombre.required' => 'El campo nombre es obligatorio.',
            'ap_pat.required' => 'El campo apellido paterno es obligatorio.',
            'ap_mat.required' => 'El campo apellido materno es obligatorio.',
            'dni.required' => 'El campo DNI es obligatorio.',
            'email.email' => 'El campo email debe ser una dirección de correo electrónico válida.',
            'email.unique' => 'El campo email ya está en uso.',
        ]);
        $proveedor = Proveedor::find($id);
        $proveedor->update([
            'nombre' => $request->nombre,
            'ap_pat' => $request->ap_pat,
            'ap_mat' => $request->ap_mat,
            'email' => $request->email,
            'dni' => $request->dni
        ]);

        return response()->json(['message' => 'Proveedor actualizado correctamente'], 201);
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::find($id);
        $proveedor->delete();
        return response()->json(['message' => 'Proveedor eliminado correctamente'], 200);
    }
}
