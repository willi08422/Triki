<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function registrar(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:255|unique:usuarios',
        ]);

        $usuario = Usuario::create([
            'nickname' => $request->nickname,
        ]);

        return response()->json($usuario, 201);
    }
}

