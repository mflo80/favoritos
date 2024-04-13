<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as Https;

class AjustesController extends Controller
{
    public function index()
    {
        $clienteIP = request()->ip();

        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->get(getenv('AJUSTES') . "?clienteIP=" . $clienteIP);

        $ajustes = json_decode($response->body(), true);

        if ($response->getStatusCode() === 404) {
            $ajustes = [
                "clienteIP" => $clienteIP,
                "imagenFondo" => null,
                "colorFondoA" => "#004985",
                "colorFondoB" => "#001c33",
                "tipo" => "color",
                "boxSize" => 150,
                "boxColor" => "#ffffff"
            ];

            $response = Https::withHeaders([
                "Accept" => "application/json",
            ])->withOptions([
                'verify' => false,
            ])->post(getenv('AJUSTES'), $ajustes);

            $ajustes = json_decode($response->body(), true);

            if ($response->getStatusCode() === 201) {
                $ajustes = $ajustes['data'][0];
                $estado = true;
            }
        }

        if ($response->getStatusCode() === 200) {
            $ajustes = $ajustes['data'][0];
            $estado = true;
        }

        if ($estado) {
            $fondos = array_diff(scandir(public_path('img/wallpapers')), array('.', '..'));
            return view('favoritos.ajustes', compact('ajustes', 'fondos'));
        }

        return redirect()->route('favoritos.error-500')->withErrors([
            'message' => "Error al cargar los ajustes."
        ]);
    }

    public function guardar(Request $request)
    {
        $clienteIP = request()->ip();

        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->get(getenv('AJUSTES') . "?clienteIP=" . $clienteIP);

        $ajustes = json_decode($response->body(), true);

        if ($response->getStatusCode() === 404) {
            return redirect()->route('ajustes.index')->withErrors([
                'message' => "No se encontraron ajustes para el cliente."
            ]);
        }

        if ($response->getStatusCode() === 200) {
            $ajustes = $ajustes['data'][0];

            if ($ajustes['clienteIP'] !== $clienteIP) {
                return redirect()->route('ajustes.index')->withErrors([
                    'message' => "No se encontraron ajustes para el cliente."
                ]);
            }

            if($request->input('tipoFondo') === 'imagen') {
                $request->validate([
                    'imagenFondo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                ],[
                    'imagenFondo.required' => 'La imagen de fondo es requerida.',
                    'imagenFondo.image' => 'El archivo debe ser una imagen.',
                    'imagenFondo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
                    'imagenFondo.max' => 'La imagen no debe pesar más de 5MB.'
                ]);
            }

            if($request->input('tipoFondo') === 'color') {
                $request->validate([
                    'colorFondoA' => 'required|string|max:7',
                    'colorFondoB' => 'required|string|max:7',
                    'boxColor' => 'required|string|max:7',
                ],[
                    'colorFondoA.required' => 'El color de fondo primario es requerido.',
                    'colorFondoA.string' => 'El color de fondo primario debe ser una cadena de texto.',
                    'colorFondoA.max' => 'El color de fondo primario no debe ser mayor a 7 caracteres.',
                    'colorFondoB.required' => 'El color de fondo secundario es requerido.',
                    'colorFondoB.string' => 'El color de fondo secundario debe ser una cadena de texto.',
                    'colorFondoB.max' => 'El color de fondo secundario no debe ser mayor a 7 caracteres.',
                    'boxColor.required' => 'El color de la caja es requerido.',
                    'boxColor.string' => 'El color de la caja debe ser una cadena de texto.',
                    'boxColor.max' => 'El color de la caja no debe ser mayor a 7 caracteres.',
                ]);
            }

            $filename = $ajustes['imagenFondo'];

            if ($request->input('tipoFondo') === 'predef') {
                $originalImage = public_path('img/wallpapers/' . $request->imagenFondoDef);
                $filename = time() . '.' . pathinfo($request->imagenFondoDef, PATHINFO_EXTENSION);
                $newImage = public_path('img/' . $filename);

                if ($ajustes['imagenFondo'] !== null){
                    unlink(public_path('img/' . $ajustes['imagenFondo']));
                }

                if (file_exists($originalImage)) {
                    copy($originalImage, $newImage);
                } else {
                    return redirect()->route('ajustes.index')->withErrors([
                        'message' => "Error al cargar la imagen de fondo predefinida."
                    ])->withInput();
                }
            }

            if($request->input('tipoFondo') === 'imagen' && $request->hasFile('imagenFondo')) {
                $file = $request->file('imagenFondo');
                $filename = time() . '.' . $file->getClientOriginalExtension();

                if ($ajustes['imagenFondo'] !== null){
                    unlink(public_path('img/' . $ajustes['imagenFondo']));
                }

                $tempPath = $file->move(sys_get_temp_dir(), $filename);

                $src = imagecreatefromstring(file_get_contents($tempPath));
                $width_orig = imagesx($src);
                $height_orig = imagesy($src);

                $width = 1920;
                $height = 1080;

                $image_p = imagecreatetruecolor($width, $height);

                imagecopyresampled($image_p, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagepng($image_p, public_path('img/' . $filename));

                imagedestroy($image_p);
                imagedestroy($src);
            }

            if($request->input('tipoFondo') === 'color') {
                if ($ajustes['imagenFondo'] !== null){
                    unlink(public_path('img/' . $ajustes['imagenFondo']));
                }

                $filename = null;
            }

            $datos = [
                "clienteIP" => $clienteIP,
                "imagenFondo" => $filename,
                "colorFondoA" => $request->input('colorFondoA'),
                "colorFondoB" => $request->input('colorFondoB'),
                "tipo" => $request->input('tipoFondo'),
                "boxSize" => $request->input('boxSize'),
                "boxColor" => $request->input('boxColor')
            ];

            $clienteIP = urlencode($ajustes['clienteIP']);

            $response = Https::withHeaders([
                "Accept" => "application/json",
            ])->withOptions([
                'verify' => false,
            ])->put(getenv('AJUSTES') . "/" . $clienteIP, $datos);

            if ($response->getStatusCode() === 200) {
                return redirect()->route('favoritos.index')->with([
                    'message' => "Ajustes guardados correctamente."
                ]);
            }

            return redirect()->route('ajustes.index')->withErrors([
                'message' => "Error al guardar los ajustes."
            ]);
        }

        return redirect()->route('ajustes.index')->withErrors([
            'message' => "Error al guardar los ajustes."
        ]);
    }
}
