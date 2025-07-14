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

    /**
     * Exporta todas las opiniones a un archivo CSV.
     */
    public function export()
    {
        $opinions = Opinion::all();
        $fileName = 'opiniones_turisticas.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = ['ID', 'Title', 'Review', 'Polarity', 'Town', 'Region', 'Type', 'Usuario', 'Created At'];

        $callback = function() use($opinions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($opinions as $opinion) {
                $row['ID']         = $opinion->id;
                $row['Title']      = $opinion->title;
                $row['Review']     = $opinion->review;
                $row['Polarity']   = $opinion->polarity;
                $row['Town']       = $opinion->town;
                $row['Region']     = $opinion->region;
                $row['Type']       = $opinion->type;
                $row['Usuario']    = $opinion->usuario;
                $row['Created At'] = $opinion->created_at;

                fputcsv($file, [
                    $row['ID'], 
                    $row['Title'], 
                    $row['Review'], 
                    $row['Polarity'], 
                    $row['Town'], 
                    $row['Region'], 
                    $row['Type'], 
                    $row['Usuario'], 
                    $row['Created At']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}