<?php

namespace App\Http\Controllers;

use App\Models\Favoritos;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FavoritosController extends Controller
{
    public function buscar()
    {
        try {
            $favoritos = Cache::remember('favoritos', 3600, function () {
                return Favoritos::all();
            });

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
            $favoritos = Cache::remember('favoritos_' . $clienteIP, 3600, function () use ($clienteIP) {
                return Favoritos::where('clienteIP', $clienteIP)->get();
            });

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
            Cache::forget('favoritos');
            Cache::forget('favoritos_' . $request->clienteIP);

            $favorito = new Favoritos();
            $favorito->clienteIP = $request->clienteIP;
            $favorito->url = $request->url;
            $favorito->nombre = $request->nombre;
            $favorito->imagenFondo = $request->imagenFondo;
            $favorito->colorFondoA = $request->colorFondoA;
            $favorito->colorFondoB = $request->colorFondoB;
            $favorito->colorTexto = $request->colorTexto;
            $favorito->tipo = $request->tipo;
            $favorito->fechaCreacion = Carbon::now('America/Montevideo');
            $favorito->fechaActualizacion = null;
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
            Cache::forget('favoritos');
            Cache::forget('favoritos_' . $request->clienteIP);

            $favorito = Favoritos::find($id);
            $favorito->clienteIP = $request->clienteIP;
            $favorito->url = $request->url;
            $favorito->nombre = $request->nombre;
            $favorito->imagenFondo = $request->imagenFondo;
            $favorito->colorFondoA = $request->colorFondoA;
            $favorito->colorFondoB = $request->colorFondoB;
            $favorito->colorTexto = $request->colorTexto;
            $favorito->tipo = $request->tipo;
            $favorito->fechaActualizacion = Carbon::now('America/Montevideo');
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

    public function actualizarOrden($idCambiado1, $idCambiado2, $clienteIP)
    {
        try {
            $clienteIP = urldecode($clienteIP);

            Cache::forget('favoritos');
            Cache::forget('favoritos_' . $clienteIP);

            $elemento1 = Favoritos::where('id', $idCambiado1)->first();
            $elemento2 = Favoritos::where('id', $idCambiado2)->first();

            if (!$elemento1 || !$elemento2) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Uno o ambos elementos no se encontraron'
                ], 404);
            }

            $temp = clone $elemento1;

            $elemento1->clienteIP = $elemento2->clienteIP;
            $elemento1->url = $elemento2->url;
            $elemento1->nombre = $elemento2->nombre;
            $elemento1->imagenFondo = $elemento2->imagenFondo;
            $elemento1->colorFondoA = $elemento2->colorFondoA;
            $elemento1->colorFondoB = $elemento2->colorFondoB;
            $elemento1->colorTexto = $elemento2->colorTexto;
            $elemento1->tipo = $elemento2->tipo;
            $elemento1->fechaActualizacion = Carbon::now('America/Montevideo');

            $elemento2->clienteIP = $temp->clienteIP;
            $elemento2->url = $temp->url;
            $elemento2->nombre = $temp->nombre;
            $elemento2->imagenFondo = $temp->imagenFondo;
            $elemento2->colorFondoA = $temp->colorFondoA;
            $elemento2->colorFondoB = $temp->colorFondoB;
            $elemento2->colorTexto = $temp->colorTexto;
            $elemento2->tipo = $temp->tipo;
            $elemento2->fechaActualizacion = Carbon::now('America/Montevideo');

            $elemento1->save();
            $elemento2->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Orden actualizado'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }

    public function eliminar(Request $request, $id)
    {
        try {
            $clienteIP = urldecode($request->clienteIP);

            Cache::forget('favoritos');
            Cache::forget('favoritos_' . $clienteIP);

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
