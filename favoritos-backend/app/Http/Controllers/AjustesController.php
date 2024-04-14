<?php

namespace App\Http\Controllers;

use App\Models\Ajustes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class AjustesController extends Controller
{
    public function index()
    {
        try {
            $ajustes = Ajustes::all();

            if ($ajustes && count($ajustes) > 0) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ajustes encontrados',
                    'data' => $ajustes
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron ajustes'
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error inesperado',
                'data' => $th
            ], 500);
        }
    }

    public function buscar($clienteIP)
    {
        try {
            $clienteIP = urldecode($clienteIP);
            $ajustes = Ajustes::where('clienteIP', $clienteIP)->first();

            if ($ajustes) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ajustes encontrados',
                    'data' => $ajustes
                ], 200);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron ajustes para el cliente con IP ' . $clienteIP
            ], 404);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron ajustes para el cliente con IP ' . $clienteIP
            ], 404);
        } catch (\Throwable $th) {
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
            $ajustes = new Ajustes();
            $ajustes->clienteIP = $request->clienteIP;
            $ajustes->imagenFondo = $request->imagenFondo;
            $ajustes->colorFondoA = $request->colorFondoA;
            $ajustes->colorFondoB = $request->colorFondoB;
            $ajustes->tipo = $request->tipo;
            $ajustes->boxSize = $request->boxSize;
            $ajustes->boxColor = $request->boxColor;
            $ajustes->fechaCreacion = Carbon::now('America/Montevideo');
            $ajustes->fechaActualizacion = null;
            $ajustes->save();

            return response()->json(
                [   'status' => 'success',
                    'message' => 'Ajustes guardados',
                    'data' => $ajustes
                ], 201);
        } catch (\Throwable $th) {
            return response()->json(
                [   'status' => 'error',
                    'message' => 'Error al guardar los ajustes',
                    'data' => $th
                ], 500);
        }
    }

    public function modificar(Request $request, $clienteIP)
    {
        try {
            $clienteIP = urldecode($clienteIP);
            $ajustes = Ajustes::where('clienteIP', $clienteIP)->first();
            $ajustes->imagenFondo = $request->imagenFondo;
            $ajustes->colorFondoA = $request->colorFondoA;
            $ajustes->colorFondoB = $request->colorFondoB;
            $ajustes->tipo = $request->tipo;
            $ajustes->boxSize = $request->boxSize;
            $ajustes->boxColor = $request->boxColor;
            $ajustes->fechaActualizacion = Carbon::now('America/Montevideo');
            $ajustes->save();

            return response()->json(
                [   'status' => 'success',
                    'message' => 'Ajustes modificados',
                    'data' => $ajustes
                ], 200);
        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se encontraron ajustes para el cliente con IP ' . $clienteIP
            ], 404);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al modificar los ajustes',
                'data' => $th
            ], 500);
        }
    }
}
