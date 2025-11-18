<?php 

class UsuariosRepository
{
    /**
     * Esto guarda la foto nueva de un jugador en la base de datos.
     */
    public static function actualizarAvatar($avatar, $user)
    {
        $db = Connection::connect();
        $q = "UPDATE users SET avatar = '" . $avatar . "' WHERE id = " . $user->getId();
        $db->query($q);
        return $db->affected_rows;
    }

    /**
     * Esto crea un nuevo jugador en la base de datos con su nombre y contraseña.
     */
    public static function registrar($user, $contrasena)
    {
        $db = Connection::connect();
        // Usamos los nombres de columna de tu nueva tabla: username y password_hash
        $q = "INSERT INTO users (username, password_hash) VALUES ('" . $user->getUsername() . "', md5('" . $contrasena . "'))";
        $db->query($q);
        $userId = $db->insert_id;
        // Creamos la entrada correspondiente en la tabla de puntuaciones (scores)
        $db->query("INSERT INTO scores (user_id) VALUES (" . $userId . ")");
        return $userId;
    }

    /**
     * Esto busca a un jugador en la base de datos usando su número de identificación.
     */
    public static function obtenerPorId($id)
    {
        $db = Connection::connect();
        $q = "SELECT * FROM users WHERE id=" . $id;
        $result = $db->query($q);

        if ($row = $result->fetch_assoc()) {
            return new Usuarios($row['id'], $row['username'], $row['avatar']);
        }
        return null;
    }

    /**
     * Busca a un jugador por su nombre de usuario. Útil para ver si ya existe.
     */
    public static function obtenerPorNombre($nombre_usuario)
    {
        $db = Connection::connect();
        $q = "SELECT * FROM users WHERE username = '" . $nombre_usuario . "'";
        $result = $db->query($q);
        
        if ($userRow = $result->fetch_assoc()) {
            return new Usuarios($userRow['id'], $userRow['username'], $userRow['avatar']);
        }

        return null;
    }

    /**
     * Esto comprueba si el nombre y la contraseña que nos dan son correctos.
     */
    public static function iniciarSesion($nombre_usuario, $contrasena)
    {
        $db = Connection::connect();
        $contrasena_md5 = md5($contrasena);
        $q = "SELECT * FROM users WHERE username = '" . $nombre_usuario . "' AND password_hash = '" . $contrasena_md5 . "'";
        $result = $db->query($q);
        
        if ($userRow = $result->fetch_assoc()) {
            return new Usuarios($userRow['id'], $userRow['username'], $userRow['avatar']);
        }

        return null;
    }

    /**
     * Esto guarda en la base de datos cuántas partidas ha jugado, ganado y perdido un jugador.
     */
    public static function actualizarEstadisticas($user)
    {
        $db = Connection::connect();
        // Ahora actualizamos la tabla 'scores' en lugar de 'users'
        $q = "UPDATE scores SET " .
             "total_games = " . $user->getTotalGames() . ", " .
             "wins = " . $user->getWins() . ", " .
             "losses = " . $user->getLosses() . " " .
             "WHERE user_id = " . $user->getId();
        $db->query($q);
        return $db->affected_rows;
    }
}