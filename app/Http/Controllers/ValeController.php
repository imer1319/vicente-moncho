<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Proveedor;
use App\Models\Valet;
use App\Models\ValeItem;
use Barryvdh\DomPDF\Facade\Pdf;

class ValeController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all()->map(function ($proveedor) {
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
        return view('admin.valets.index', compact('proveedores'));
    }

    public function apivalets(Request $request)
    {
     return Valet::with('items')
     ->when($request->has('proveedor_id'), function ($query) use ($request) {
        return $query->where('proveedor_id', $request->proveedor_id);
    })
     ->orderBy('created_at','DESC')
     ->get()
     ->map(function ($valet) {
        return [
            'id' => $valet->id,
            'nombre' => $valet->nombre,
            'proveedor_id' => $valet->proveedor_id,
            'proveedor' => $valet->proveedor->nombre,
            'items' => $valet->items
        ];
    });
 }

 public function store(Request $request)
 {
    $request->validate([
        'nombre' => 'required',
        'proveedor_id' => 'required|numeric|exists:proveedors,id',
        'items'=> 'array|required',
        'items.*.descripcion' => 'required|string|max:255',
        'items.*.cantidad' => 'required|numeric',
        'items.*.importe' => 'nullable',
    ],
    [
        'nombre.required' => 'El campo nombre es obligatorio.',
        'proveedor_id.required' => 'El campo proveedor es obligatorio.',
        'proveedor_id.numeric' => 'El campo proveedor debe ser un valor numérico.',
        'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
        'items.required' => 'Debe proporcionar al menos un ítem.',
        'items.array' => 'El formato de los ítems es incorrecto.',
        'items.*.descripcion.required' => 'La descripción del ítem es obligatoria.',
        'items.*.descripcion.string' => 'La descripción del ítem debe ser una cadena de caracteres.',
        'items.*.descripcion.max' => 'La descripción del ítem no puede tener más de 255 caracteres.',
        'items.*.cantidad.required' => 'La cantidad del ítem es obligatoria.',
        'items.*.cantidad.numeric' => 'La cantidad del ítem debe ser un valor numérico.',
        'items.*.importe.nullable' => 'El importe del ítem debe ser un valor válido.', 
    ]);

    $vale = new Valet();
    $vale->nombre = $request->input('nombre');
    $vale->proveedor_id = $request->input('proveedor_id');
    $vale->save();

    foreach ($request->input('items') as $item) {
        $valeItem = new ValeItem();
        $valeItem->valet_id = $vale->id;
        $valeItem->descripcion = $item['descripcion'];
        $valeItem->cantidad = $item['cantidad'];
        $valeItem->importe = $item['importe'] ?? null;
        $valeItem->save();
    }

    return response()->json(['message' => 'Vale creado correctamente'], 201);
}

public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required',
        'proveedor_id' => 'required|numeric|exists:proveedors,id',
        'items'=> 'array|required',
        'items.*.descripcion' => 'required|string|max:255',
        'items.*.cantidad' => 'required|numeric',
        'items.*.importe' => 'nullable',
    ],
    [
        'nombre.required' => 'El campo nombre es obligatorio.',
        'proveedor_id.required' => 'El campo proveedor es obligatorio.',
        'proveedor_id.numeric' => 'El campo proveedor debe ser un valor numérico.',
        'proveedor_id.exists' => 'El proveedor seleccionado no es válido.',
        'items.required' => 'Debe proporcionar al menos un ítem.',
        'items.array' => 'El formato de los ítems es incorrecto.',
        'items.*.descripcion.required' => 'La descripción del ítem es obligatoria.',
        'items.*.descripcion.string' => 'La descripción del ítem debe ser una cadena de caracteres.',
        'items.*.descripcion.max' => 'La descripción del ítem no puede tener más de 255 caracteres.',
        'items.*.cantidad.required' => 'La cantidad del ítem es obligatoria.',
        'items.*.cantidad.numeric' => 'La cantidad del ítem debe ser un valor numérico.',
        'items.*.importe.nullable' => 'El importe del ítem debe ser un valor válido.', 
    ]);

    $vale = Valet::findOrFail($id);
    $vale->nombre = $request->input('nombre');
    $vale->proveedor_id = $request->input('proveedor_id');
    $vale->save();

    $vale->items()->delete();

    foreach ($request->input('items') as $item) {
        $valeItem = new ValeItem();
        $valeItem->valet_id = $vale->id;
        $valeItem->descripcion = $item['descripcion'];
        $valeItem->cantidad = $item['cantidad'];
        $valeItem->importe = $item['importe'] ?? null;
        $valeItem->save();
    }

    return response()->json(['message' => 'Vale actualizado correctamente'], 200);
}

public function destroy($id)
{
    $proveedor = Proveedor::find($id);
    $proveedor->delete();
    return response()->json(['message' => 'Vale eliminado correctamente'], 200);
}

public function reportes($id)
{
    $valet = Valet::with('items','proveedor')->findOrFail($id);

    $pdf = PDF::loadView('admin.valets.pdf', [
        'valet' => $valet,
    ]);

    $this->configureSSLContext($pdf);

    $pdf->setPaper('A4', 'landscape');

    $fileName = "VALET -" . date('Y-m-d') . ".pdf";

    return $pdf->stream($fileName);
}

public function configureSSLContext($pdf)
{
    $pdf->getDomPDF()->setHttpContext(
        stream_context_create([
            'ssl' => [
                'allow_self_signed' => true,
                'verify_peer' => false,
                'verify_peer_name' => false,
            ]
        ])
    );
}
}
