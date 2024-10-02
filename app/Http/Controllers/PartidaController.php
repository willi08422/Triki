<?php

namespace App\Http\Controllers;

use App\Models\Partida;
use Illuminate\Http\Request;
use App\Services\TrikiService;

class PartidaController extends Controller
{
    private $trikiService;

    public function __construct(TrikiService $trikiService)
    {
        $this->trikiService = $trikiService;
    }

    public function crear(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'puntaje' => 'required|integer|min:0',
        ]);

        $partida = Partida::create([
            'usuario_id' => $request->usuario_id,
            'puntaje' => $request->puntaje,
        ]);

        return response()->json($partida, 201);
    }

    public function obtenerPartidas(Request $request)
    {
        $partidas = Partida::with('usuario')->get();
        return response()->json($partidas);
    }

    public function iniciarPartida(Request $request)
    {
        $board = $this->trikiService->iniciarPartida();

        return response()->json([
            'success' => true,
            'tablero' => $board,
        ]);
    }

    public function hacerMovimiento(Request $request)
    {
        $row = $request->input('row');
        $col = $request->input('col');
        $usuarioId = $request->input('usuario_id');  // Si lo necesitas

        $resultado = $this->trikiService->hacerMovimiento($row, $col, $usuarioId);

        return response()->json($resultado);

        if (!$resultado['success']) {
            return response()->json(['mensaje' => $resultado['message']], 400);
        }

        // Verificar si hay un ganador
        if ($resultado['winner']) {
            // Finalizar el juego y registrar el resultado
            $puntaje = 100; // Cambia esto según tu lógica de puntaje
            $this->trikiService->finalizarJuego($request->usuario_id, $puntaje);
            
            return response()->json([
                'mensaje' => 'Juego terminado',
                'ganador' => $resultado['winner'],
                'tablero' => $resultado['board'],
                'currentPlayer' => null, // No hay jugador actual si el juego ha terminado
            ]);
        }
        
        // Verificar si currentPlayer es null antes de cambiar al siguiente jugador
        if (is_null($resultado['currentPlayer'])) {
            return response()->json([
                'mensaje' => 'El juego ha terminado, no se puede realizar más movimientos.',
                'tablero' => $resultado['board'],
                'currentPlayer' => null,
            ]);
        }
        
    }

    public function finalizarPartida(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'puntaje' => 'required|integer|min:0',
        ]);

        $puntaje = $request->puntaje; 
        $partida = $this->trikiService->finalizarJuego($request->usuario_id, $puntaje);

        return response()->json($partida, 201);
    }
}






