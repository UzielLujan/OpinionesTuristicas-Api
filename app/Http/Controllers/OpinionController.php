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
        // Busca todas las opiniones en la base de datos y las devuelve como JSON
        $opinions = Opinion::all();
        return response()->json($opinions);
    }

    /**
     * Almacena una nueva opinión en la base de datos.
     */
    public function store(Request $request)
    {
        // Valida los datos de entrada
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

        // Crea la nueva opinión con los datos validados
        $opinion = Opinion::create($validatedData);

        // Devuelve la opinión creada con un código de estado 201 (Created)
        return response()->json($opinion, 201);
    }
}