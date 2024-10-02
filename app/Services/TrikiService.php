<?php

namespace App\Services;

use App\Models\Partida;

class trikiService
{
    private $board;
    private $currentPlayer;
    private $winner;

    public function __construct()
    {
        $this->resetGame();
    }

    public function resetGame()
    {
        $this->board = [['', '', ''], ['', '', ''], ['', '', '']];
        $this->currentPlayer = 'X'; 
        $this->winner = null;
    }

    public function iniciarPartida()
    {
        $this->resetGame();
        return $this->getBoard(); // Retorna el estado inicial del tablero
    }

    public function makeMove($row, $col)
    {
        // Validación del movimiento
        if ($this->board[$row][$col] !== '') {
            return ['success' => false, 'message' => 'Movimiento inválido, el espacio ya está ocupado.'];
        }

        $this->board[$row][$col] = $this->currentPlayer;
        $this->winner = $this->checkWinner();

        // Cambia al siguiente jugador
        $nextPlayer = $this->currentPlayer === 'X' ? 'O' : 'X';
        $this->currentPlayer = $nextPlayer;

        return [
            'success' => true,
            'board' => $this->getBoard(),
            'winner' => $this->winner,
            'nextPlayer' => $nextPlayer, // Devuelve el siguiente jugador
        ];
    }

    public function makeRandomMove()
    {
        $emptyCells = [];
        for ($row = 0; $row < 3; $row++) {
            for ($col = 0; $col < 3; $col++) {
                if ($this->board[$row][$col] === '') {
                    $emptyCells[] = [$row, $col];
                }
            }
        }

        if (!empty($emptyCells)) {
            $randomCell = $emptyCells[array_rand($emptyCells)];
            $this->makeMove($randomCell[0], $randomCell[1]);
        }
    }

    public function checkWinner()
    {
        // Verifica filas, columnas y diagonales
        for ($i = 0; $i < 3; $i++) {
            if ($this->board[$i][0] && $this->board[$i][0] === $this->board[$i][1] && $this->board[$i][1] === $this->board[$i][2]) {
                return $this->board[$i][0];
            }
            if ($this->board[0][$i] && $this->board[0][$i] === $this->board[1][$i] && $this->board[1][$i] === $this->board[2][$i]) {
                return $this->board[0][$i];
            }
        }

        if ($this->board[0][0] && $this->board[0][0] === $this->board[1][1] && $this->board[1][1] === $this->board[2][2]) {
            return $this->board[0][0];
        }

        if ($this->board[0][2] && $this->board[0][2] === $this->board[1][1] && $this->board[1][1] === $this->board[2][0]) {
            return $this->board[0][2];
        }

        return null; // Sin ganador
    }

    public function getBoard()
    {
        return $this->board;
    }

    public function finalizarJuego($usuarioId, $puntaje)
    {
        // Se guarda el resultado en la base de datos
        $partida = Partida::create([
            'usuario_id' => $usuarioId,
            'puntaje' => $puntaje,
        ]);

        return $partida; // Retorna la partida registrada
    }

    // Método para hacer el movimiento y recibir el usuario_id
    public function hacerMovimiento($row, $col, $usuarioId)
    {
        $resultado = $this->makeMove($row, $col);

        // Almacena el resultado si hay un ganador
        if ($resultado['winner']) {
            $this->finalizarJuego($usuarioId, 100); 
        }

        return $resultado;
    }
}



