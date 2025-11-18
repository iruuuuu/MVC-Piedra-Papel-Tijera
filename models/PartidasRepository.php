<?php

require_once 'Partidas.php';

class PartidasRepository
{
    /**
     * Crea una nueva partida en la base de datos con los jugadores y la hora de inicio.
     * Devuelve el ID de la partida creada.
     */
    public static function crearPartida($player_x_id, $player_o_id)
    {
        $db = Connection::connect();
        // Asumimos que player_o_id puede ser null si es contra la máquina sin registrar.
        $player_o_id_sql = $player_o_id ? $player_o_id : "NULL";

        $q = "INSERT INTO games (player_x_id, player_o_id, start_time) VALUES (" . $player_x_id . ", " . $player_o_id_sql . ", NOW())";
        $db->query($q);
        return $db->insert_id;
    }

    /**
     * Actualiza una partida existente con el resultado final.
     */
    public static function actualizarPartida($partida)
    {
        $db = Connection::connect();

        $winner_id_sql = $partida->getWinnerId() ? $partida->getWinnerId() : "NULL";

        $q = "UPDATE games SET " .
             "winner_id = " . $winner_id_sql . ", " .
             "board_history = '" . $partida->getBoardHistory() . "', " .
             "end_time = NOW(), " .
             "game_result = '" . $partida->getGameResult() . "' " .
             "WHERE id = " . $partida->getId();

        $db->query($q);
        return $db->affected_rows;
    }

    /**
     * Obtiene todas las partidas de un usuario específico.
     */
    public static function obtenerPartidasPorUsuario($userId)
    {
        $db = Connection::connect();
        $q = "SELECT * FROM games WHERE player_x_id = " . $userId . " OR player_o_id = " . $userId . " ORDER BY start_time DESC";
        $result = $db->query($q);
        $partidas = [];

        while ($row = $result->fetch_assoc()) {
            $partidas[] = new Partidas(
                $row['id'], $row['player_x_id'], $row['player_o_id'],
                $row['winner_id'], $row['board_history'], $row['start_time'],
                $row['end_time'], $row['game_result']
            );
        }

        return $partidas;
    }

    /**
     * Ejecuta una ronda del juego Piedra, Papel o Tijera.
     * No interactúa con la BD, solo contiene la lógica del juego.
     */
    public static function jugarRonda($jugadaUsuario) {
        $opciones = ['piedra', 'papel', 'tijera'];
        
        // 1. La máquina elige una jugada al azar.
        $jugadaMaquina = $opciones[array_rand($opciones)];
        
        // 2. Se determina el resultado.
        $resultado = '';
        if ($jugadaUsuario == $jugadaMaquina) {
            $resultado = 'empate';
        } elseif (
            ($jugadaUsuario == 'piedra' && $jugadaMaquina == 'tijera') ||
            ($jugadaUsuario == 'tijera' && $jugadaMaquina == 'papel') ||
            ($jugadaUsuario == 'papel' && $jugadaMaquina == 'piedra')
        ) {
            $resultado = 'victoria';
        } else {
            $resultado = 'derrota';
        }
        
        // 3. El método devuelve la jugada de la máquina y el resultado.
        return ['maquina' => $jugadaMaquina, 'resultado' => $resultado];
    }
}