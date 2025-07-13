<?php

namespace App\Http\Controllers;

use App\Models\Opinion;
use Illuminate\Http\Request;

class OpinionController extends Controller
{
    /**
     * Muestra una lista de todas las opiniones.
     */
    public function index()
    {
        $opinions = Opinion::all();
        return response()->json($opinions);
    }

    /**
     * Almacena una nueva opinión en la base de datos.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'review' => 'required|string',
            'polarity' => 'required|integer|between:1,5',
            'town' => 'required|string|max:100',
            'region' => 'required|string|max:100',
            'type' => 'required|string|in:Hotel,Restaurant,Attractive',
            'usuario' => 'required|string|max:100',
            'etiquetado_manual' => 'sometimes|boolean',
        ]);

        $opinion = Opinion::create($validatedData);

        return response()->json($opinion, 201);
    }

    /**
     * Muestra una opinión específica.
     */
    public function show(string $id)
    {
        $opinion = Opinion::findOrFail($id);
        return response()->json($opinion);
    }

    /**
     * Actualiza una opinión específica.
     */
    public function update(Request $request, string $id)
    {
        $opinion = Opinion::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'sometimes|string|max:255',
            'review' => 'sometimes|string',
            'polarity' => 'sometimes|integer|between:1,5',
            'town' => 'sometimes|string|max:100',
            'region' => 'sometimes|string|max:100',
            'type' => 'sometimes|string|in:Hotel,Restaurant,Attractive',
            'usuario' => 'sometimes|string|max:100',
        ]);

        $opinion->update($validatedData);

        return response()->json($opinion);
    }

    /**
     * Elimina una opinión específica.
     */
    public function destroy(string $id)
    {
        $opinion = Opinion::findOrFail($id);
        $opinion->delete();

        return response()->json(null, 204); // 204 No Content
    }
}