<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http as Https;
use Illuminate\Support\Facades\Log;

class FavoritosController extends Controller
{
    /**
     * Show the list of favorite pages.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $clienteIP = request()->ip();

        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->get(getenv('AJUSTES') . "/" . urlencode($clienteIP));

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
                $ajustes = $ajustes['data'];
                $estado = true;
            }
        }

        if ($response->getStatusCode() === 200) {
            $ajustes = $ajustes['data'];
            $estado = true;
        }

        if ($estado) {
            $response = Https::withHeaders([
                "Accept" => "application/json",
            ])->withOptions([
                'verify' => false,
            ])->get(getenv('FAVORITOS') . "/cliente/" . $clienteIP);

            $favoritos = json_decode($response->body(), true);

            if ($response->getStatusCode() === 404) {
                $favoritos = [];
            }

            if ($response->getStatusCode() === 200) {
                $favoritos = $favoritos['data'];
            }

            if ($response->getStatusCode() === 500) {
                return redirect()->route('favoritos.error-500')->withErrors([
                    'message' => "Error al cargar las páginas favoritas."
                ]);
            }

            return view('favoritos.index', compact('favoritos', 'ajustes'));
        }

        return redirect()->route('favoritos.error-500')->withErrors([
            'message' => "Error al cargar los ajustes."
        ]);
    }

    /**
     * Show the form for creating a new web page.
     *
     * @return \Illuminate\View\View
     */
    public function crear()
    {
        $logos = array_diff(scandir(public_path('img/logos')), array('.', '..'));

        return view('favoritos.crear', ['logos' => $logos]);
    }

    /**
     * Store a newly created web page in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function guardar(Request $request)
    {
        if($request->input('tipoFondo') === 'imagen') {
            $request->validate([
                'url' => 'required|url',
                'nombre' => 'required|string|max:30',
                'imagenFondo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            ],[
                'url.required' => 'La URL es requerida.',
                'url.url' => 'La URL no es válida.',
                'nombre.required' => 'El nombre es requerido.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no debe ser mayor a 30 caracteres.',
                'imagenFondo.required' => 'La imagen de fondo es requerida.',
                'imagenFondo.image' => 'El archivo debe ser una imagen.',
                'imagenFondo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
                'imagenFondo.max' => 'La imagen no debe pesar más de 5MB.'
            ]);
        }

        if($request->input('tipoFondo') === 'color') {
            $request->validate([
                'url' => 'required|url',
                'nombre' => 'required|string|max:30',
                'colorFondoA' => 'required|string|max:7',
                'colorFondoB' => 'required|string|max:7',
                'colorTexto' => 'required|string|max:7',
            ],[
                'url.required' => 'La URL es requerida.',
                'url.url' => 'La URL no es válida.',
                'nombre.required' => 'El nombre es requerido.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no debe ser mayor a 30 caracteres.',
                'colorFondoA.required' => 'El color de fondo primario es requerido.',
                'colorFondoA.string' => 'El color de fondo primario debe ser una cadena de texto.',
                'colorFondoA.max' => 'El color de fondo primario no debe ser mayor a 7 caracteres.',
                'colorFondoB.required' => 'El color de fondo secundario es requerido.',
                'colorFondoB.string' => 'El color de fondo secundario debe ser una cadena de texto.',
                'colorFondoB.max' => 'El color de fondo secundario no debe ser mayor a 7 caracteres.',
                'colorTexto.required' => 'El color de texto es requerido.',
                'colorTexto.string' => 'El color de texto debe ser una cadena de texto.',
                'colorTexto.max' => 'El color de texto no debe ser mayor a 7 caracteres.',
            ]);
        }

        if ($request->input('tipoFondo') === 'predef') {
            $originalImage = public_path('img/logos/' . $request->imagenFondoDef);
            $filename = time() . '.' . pathinfo($request->imagenFondoDef, PATHINFO_EXTENSION);
            $newImage = public_path('img/' . $filename);

            if (file_exists($originalImage)) {
                copy($originalImage, $newImage);
            } else {
                return redirect()->route('favoritos.crear')->withErrors([
                    'message' => "Error al cargar la imagen del logo predefinido."
                ])->withInput();
            }
        }

        if($request->input('tipoFondo') === 'imagen' && $request->hasFile('imagenFondo')) {
            $file = $request->file('imagenFondo');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            $tempPath = $file->move(sys_get_temp_dir(), $filename);

            $src = imagecreatefromstring(file_get_contents($tempPath));
            $width_orig = imagesx($src);
            $height_orig = imagesy($src);

            $width = 150;
            $height = 130;

            $image_p = imagecreatetruecolor($width, $height);

            imagecopyresampled($image_p, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
            imagepng($image_p, public_path('img/' . $filename));

            imagedestroy($image_p);
            imagedestroy($src);
        }

        if($request->input('tipoFondo') === 'color') {
            $width = 150;
            $height = 130;
            $image = imagecreatetruecolor($width, $height);

            $colorFondoA = $request->input('colorFondoA');
            $colorFondoB = $request->input('colorFondoB');
            $colorTexto = $request->input('colorTexto');
            list($rFondoA, $gFondoA, $bFondoA) = sscanf($colorFondoA, "#%02x%02x%02x");
            list($rFondoB, $gFondoB, $bFondoB) = sscanf($colorFondoB, "#%02x%02x%02x");
            list($rTexto, $gTexto, $bTexto) = sscanf($colorTexto, "#%02x%02x%02x");

            // Crear un degradado
            for ($i = 0; $i < $height; $i++) {
                $r = $rFondoA + ($i / $height) * ($rFondoB - $rFondoA);
                $g = $gFondoA + ($i / $height) * ($gFondoB - $gFondoA);
                $b = $bFondoA + ($i / $height) * ($bFondoB - $bFondoA);
                $color = imagecolorallocate($image, $r, $g, $b);
                imagefilledrectangle($image, 0, $i, $width, $i+1, $color);
            }

            $colorTexto = imagecolorallocate($image, $rTexto, $gTexto, $bTexto);
            $text = $request->input('nombre');

            $fontFile = public_path('font/arialbd.ttf');
            $fontSize = 12;

            $maxWidth = imagesx($image);
            $maxCharsPerLine = floor($maxWidth / ($fontSize * 0.8));

            $text = wordwrap($text, $maxCharsPerLine, "\n\n", true);
            $lines = explode("\n", $text);

            $totalTextHeight = $fontSize * count($lines);

            $y = (imagesy($image) - $totalTextHeight) / 1.8;

            foreach ($lines as $line) {
                $bbox = imagettfbbox($fontSize, 0, $fontFile, $line);
                $x = ($maxWidth - $bbox[2]) / 2;
                imagettftext($image, $fontSize, 0, $x, $y + $bbox[1], $colorTexto, $fontFile, $line);
                $y += $fontSize;
            }

            $filename = time() . '.png';
            imagepng($image, public_path('img/' . $filename));

            imagedestroy($image);
        }

        if ($filename === null) {
            return redirect()->route('favoritos.crear')->withErrors([
                'message' => "Error al agregar la página favorita."
            ])->withInput();
        }

        $favorito = [
            "clienteIP" => request()->ip(),
            "url" => $request->input('url'),
            "nombre" => $request->input('nombre'),
            "imagenFondo" => $filename,
            "colorFondoA" => $request->input('colorFondoA'),
            "colorFondoB" => $request->input('colorFondoB'),
            "colorTexto" => $request->input('colorTexto'),
            "tipo" => $request->input('tipoFondo')
        ];

        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->post(getenv('FAVORITOS'), $favorito);

        if ($response->getStatusCode() === 201) {
            return redirect()->route('favoritos.index')->withErrors([
                'success', 'Página favorita agregada correctamente.'
            ]);
        }

        return redirect()->route('favoritos.crear')->withErrors([
            'message' => "Error al agregar la página favorita."
        ])->withInput();
    }

    public function editar($id)
    {
        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->get(getenv('FAVORITOS'). "/" . $id);

        $favorito = json_decode($response->body(), true);

        if ($response->getStatusCode() === 404) {
            return redirect()->route('favoritos.index')->withErrors([
                'error', 'Página Web no encontrada.'
            ]);
        }

        if ($response->getStatusCode() === 200) {
            $favorito = $favorito['data'];

            if ($favorito['clienteIP'] !== request()->ip()) {
                return redirect()->route('favoritos.index')->withErrors([
                    'error', 'Página Web no encontrada.'
                ]);
            }

            $logos = array_diff(scandir(public_path('img/logos')), array('.', '..'));
            return view('favoritos.editar', ['favorito' => $favorito, 'logos' => $logos]);
        }

        return redirect()->route('favoritos.index')->withErrors([
            'message' => "Error al cargar la página favorita."
        ]);
    }

    public function modificar(Request $request, $id)
    {
        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->get(getenv('FAVORITOS'). "/" . $id);

        $favorito = json_decode($response->body(), true);

        if ($response->getStatusCode() === 404) {
            return redirect()->route('favoritos.index')->withErrors([
                'message', 'Página Web no encontrada.'
            ]);
        }

        if ($response->getStatusCode() === 200) {
            $favorito = $favorito['data'];

            if ($favorito['clienteIP'] !== request()->ip()) {
                return redirect()->route('favoritos.index')->withErrors([
                    'message', 'Página Web no encontrada.'
                ]);
            }

            if($request->input('tipoFondo') === 'imagen') {
                $request->validate([
                    'url' => 'required|url',
                    'nombre' => 'required|string|max:30',
                    'imagenFondo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
                ],[
                    'url.required' => 'La URL es requerida.',
                    'url.url' => 'La URL no es válida.',
                    'nombre.required' => 'El nombre es requerido.',
                    'nombre.string' => 'El nombre debe ser una cadena de texto.',
                    'nombre.max' => 'El nombre no debe ser mayor a 30 caracteres.',
                    'imagenFondo.required' => 'La imagen de fondo es requerida.',
                    'imagenFondo.image' => 'El archivo debe ser una imagen.',
                    'imagenFondo.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, svg.',
                    'imagenFondo.max' => 'La imagen no debe pesar más de 5MB.'
                ]);
            }

            if($request->input('tipoFondo') === 'color') {
                $request->validate([
                    'url' => 'required|url',
                    'nombre' => 'required|string|max:30',
                    'colorFondoA' => 'required|string|max:7',
                    'colorFondoB' => 'required|string|max:7',
                    'colorTexto' => 'required|string|max:7',
                ],[
                    'url.required' => 'La URL es requerida.',
                    'url.url' => 'La URL no es válida.',
                    'nombre.required' => 'El nombre es requerido.',
                    'nombre.string' => 'El nombre debe ser una cadena de texto.',
                    'nombre.max' => 'El nombre no debe ser mayor a 30 caracteres.',
                    'colorFondoA.required' => 'El color de fondo primario es requerido.',
                    'colorFondoA.string' => 'El color de fondo primario debe ser una cadena de texto.',
                    'colorFondoA.max' => 'El color de fondo primario no debe ser mayor a 7 caracteres.',
                    'colorFondoB.required' => 'El color de fondo secundario es requerido.',
                    'colorFondoB.string' => 'El color de fondo secundario debe ser una cadena de texto.',
                    'colorFondoB.max' => 'El color de fondo secundario no debe ser mayor a 7 caracteres.',
                    'colorTexto.required' => 'El color de texto es requerido.',
                    'colorTexto.string' => 'El color de texto debe ser una cadena de texto.',
                    'colorTexto.max' => 'El color de texto no debe ser mayor a 7 caracteres.',
                ]);
            }

            if ($favorito['imagenFondo'] == null) {
                return redirect()->route('favoritos.editar', $favorito['id'])->withErrors([
                    'message' => "Error al cargar la imagen de la página favorita."
                ])->withInput();
            }

            $filename = $favorito['imagenFondo'];

            if ($request->input('tipoFondo') === 'predef') {
                $originalImage = public_path('img/logos/' . $request->imagenFondoDef);
                $filename = time() . '.' . pathinfo($request->imagenFondoDef, PATHINFO_EXTENSION);
                $newImage = public_path('img/' . $filename);

                if ($favorito['imagenFondo'] !== null){
                    unlink(public_path('img/' . $favorito['imagenFondo']));
                }

                if (file_exists($originalImage)) {
                    copy($originalImage, $newImage);
                } else {
                    return redirect()->route('favoritos.editar', $favorito['id'])->withErrors([
                        'message' => "Error al cargar la imagen del logo predefinido."
                    ])->withInput();
                }
            }

            if($request->input('tipoFondo') === 'imagen' && $request->hasFile('imagenFondo')) {
                $file = $request->file('imagenFondo');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                unlink(public_path('img/' . $favorito['imagenFondo']));

                $tempPath = $file->move(sys_get_temp_dir(), $filename);

                $src = imagecreatefromstring(file_get_contents($tempPath));
                $width_orig = imagesx($src);
                $height_orig = imagesy($src);

                $width = 150;
                $height = 130;

                $image_p = imagecreatetruecolor($width, $height);

                imagecopyresampled($image_p, $src, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
                imagepng($image_p, public_path('img/' . $filename));

                imagedestroy($image_p);
                imagedestroy($src);
            }

            if($request->input('tipoFondo') === 'color') {
                $width = 150;
                $height = 130;
                $image = imagecreatetruecolor($width, $height);
                unlink(public_path('img/' . $favorito['imagenFondo']));

                $colorFondoA = $request->input('colorFondoA');
                $colorFondoB = $request->input('colorFondoB');
                $colorTexto = $request->input('colorTexto');
                list($rFondoA, $gFondoA, $bFondoA) = sscanf($colorFondoA, "#%02x%02x%02x");
                list($rFondoB, $gFondoB, $bFondoB) = sscanf($colorFondoB, "#%02x%02x%02x");
                list($rTexto, $gTexto, $bTexto) = sscanf($colorTexto, "#%02x%02x%02x");

                // Crear un degradado
                for ($i = 0; $i < $height; $i++) {
                    $r = $rFondoA + ($i / $height) * ($rFondoB - $rFondoA);
                    $g = $gFondoA + ($i / $height) * ($gFondoB - $gFondoA);
                    $b = $bFondoA + ($i / $height) * ($bFondoB - $bFondoA);
                    $color = imagecolorallocate($image, $r, $g, $b);
                    imagefilledrectangle($image, 0, $i, $width, $i+1, $color);
                }

                $colorTexto = imagecolorallocate($image, $rTexto, $gTexto, $bTexto);
                $text = $request->input('nombre');

                $fontFile = public_path('font/arialbd.ttf');
                $fontSize = 12;

                $maxWidth = imagesx($image);
                $maxCharsPerLine = floor($maxWidth / ($fontSize * 0.8));

                $text = wordwrap($text, $maxCharsPerLine, "\n\n", true);
                $lines = explode("\n", $text);

                $totalTextHeight = $fontSize * count($lines);

                $y = (imagesy($image) - $totalTextHeight) / 1.8;

                foreach ($lines as $line) {
                    $bbox = imagettfbbox($fontSize, 0, $fontFile, $line);
                    $x = ($maxWidth - $bbox[2]) / 2;
                    imagettftext($image, $fontSize, 0, $x, $y + $bbox[1], $colorTexto, $fontFile, $line);
                    $y += $fontSize;
                }

                $filename = time() . '.png';
                imagepng($image, public_path('img/' . $filename));

                imagedestroy($image);
            }

            $favorito = [
                "clienteIP" => request()->ip(),
                "url" => $request->input('url'),
                "nombre" => $request->input('nombre'),
                "imagenFondo" => $filename,
                "colorFondoA" => $request->input('colorFondoA'),
                "colorFondoB" => $request->input('colorFondoB'),
                "colorTexto" => $request->input('colorTexto'),
                "tipo" => $request->input('tipoFondo')
            ];

            $response = Https::withHeaders([
                "Accept" => "application/json",
            ])->withOptions([
                'verify' => false,
            ])->put(getenv('FAVORITOS') . "/" . $id, $favorito);

            if ($response->getStatusCode() === 200) {
                return redirect()->route('favoritos.index')->withErrors([
                    'message' => "Página favorita actualizada correctamente."
                ]);
            }

            return redirect()->route('favoritos.editar', $id)->withErrors([
                'message' => "Error al actualizar la página favorita."
            ]);
        }

        return redirect()->route('favoritos.index')->withErrors([
            'message' => "Error al cargar la página favorita."
        ]);
    }

    public function eliminar($id)
    {
        $clienteIP = urlencode(request()->ip());

        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->get(getenv('FAVORITOS'). "/" . $id);

        $favorito = json_decode($response->body(), true);

        if ($response->getStatusCode() === 404) {
            return redirect()->route('favoritos.index')->withErrors([
                'message', 'Página Web no encontrada.'
            ]);
        }

        if ($response->getStatusCode() === 200) {
            $favorito = $favorito['data'];

            if ($favorito['clienteIP'] !== request()->ip()) {
                return redirect()->route('favoritos.index')->withErrors([
                    'message', 'Página Web no encontrada.'
                ]);
            }

            $response = Https::withHeaders([
                "Accept" => "application/json",
            ])->withOptions([
                'verify' => false,
            ])->delete(getenv('FAVORITOS') . "/" . $id, [
                'clienteIP' => $clienteIP
            ]);

            if ($response->getStatusCode() === 200) {
                return redirect()->route('favoritos.index')->withErrors([
                    'message', 'Página favorita eliminada correctamente.'
                ]);
            }

            return redirect()->route('favoritos.index')->withErrors([
                'message' => "Error al eliminar la página favorita."
            ]);
        }

        return redirect()->route('favoritos.index')->withErrors([
            'message' => "Error al cargar la página favorita."
        ]);
    }

    public function actualizarOrden($idCambiado1, $idCambiado2)
    {
        $clienteIP = urlencode(request()->ip());

        $response = Https::withHeaders([
            "Accept" => "application/json",
        ])->withOptions([
            'verify' => false,
        ])->put(getenv('FAVORITOS') . "/actualizarOrden/" . $idCambiado1 . "/" . $idCambiado2 . "/" . $clienteIP);

        if ($response->getStatusCode() === 200) {
            return response()->json([
                'message' => "Página favorita actualizada correctamente."
            ]);
        }

        return response()->json([
            'message' => "Error al actualizar la página favorita."
        ]);
    }
}
