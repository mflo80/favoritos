<?php

namespace App\Http\Controllers;

use App\Models\Favoritos;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FavoritosController extends Controller
{
    public function buscar()
    {
        try {
            $favoritos = Favoritos::all();

            return response()->json([
                'status' => 'success',
                'message' => 'Favoritos encontrados',
                'data' => $favoritos
            ], 200);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }

    public function buscar_favorito($id)
    {
        try {
            $favorito = Favoritos::find($id);

            if ($favorito) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Favorito encontrado',
                    'data' => $favorito
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Favorito no encontrado'
            ], 404);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }

    public function buscar_cliente($clienteIP)
    {
        try {
            $favoritos = Favoritos::where('clienteIP', $clienteIP)->get();

            if ($favoritos->count() > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Favoritos encontrados',
                    'data' => $favoritos
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Favoritos no encontrados'
            ], 404);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }

    public function guardar(Request $request)
    {
        try {
            $favorito = new Favoritos();
            $favorito->clienteIP = $request->clienteIP;
            $favorito->url = $request->url;
            $favorito->nombre = $request->nombre;
            $favorito->imagenFondo = $request->imagenFondo;
            $favorito->colorFondoA = $request->colorFondoA;
            $favorito->colorFondoB = $request->colorFondoB;
            $favorito->colorTexto = $request->colorTexto;
            $favorito->tipo = $request->tipo;
            $favorito->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Favorito guardado',
                'data' => $favorito
            ], 201);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }

    public function modificar(Request $request, $id)
    {
        try {
            $favorito = Favoritos::find($id);
            $favorito->clienteIP = $request->clienteIP;
            $favorito->url = $request->url;
            $favorito->nombre = $request->nombre;
            $favorito->imagenFondo = $request->imagenFondo;
            $favorito->colorFondoA = $request->colorFondoA;
            $favorito->colorFondoB = $request->colorFondoB;
            $favorito->colorTexto = $request->colorTexto;
            $favorito->tipo = $request->tipo;
            $favorito->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Favorito modificado',
                'data' => $favorito
            ], 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'Favorito no encontrado'
            ], 404);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }

    public function eliminar($id)
    {
        try {
            $favorito = Favoritos::find($id);

            if ($favorito) {
                $favorito->delete();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Favorito eliminado'
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Favorito no encontrado'
            ], 404);
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }
}
