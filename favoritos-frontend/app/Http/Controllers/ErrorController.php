<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function error_404()
    {
        return view('favoritos.error-404');
    }

    public function error_500()
    {
        return view('favoritos.error-500');
    }
}
