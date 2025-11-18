<?php

// 1. VERIFICACIÓN DE SESIÓN
// Si el usuario no ha iniciado sesión, se le redirige a la página de inicio.
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    die();
}

// 2. GESTIÓN DE ACCIONES (NUEVA PARTIDA)
// Si el usuario solicita reiniciar el juego.
if (isset($_GET['action']) && $_GET['action'] == 'newgame') {
    // Limpiamos los datos de la partida anterior de la sesión.
    unset($_SESSION['marcador']);
    unset($_SESSION['ultima_jugada']);
    
    // Opcional: Crear un registro de la nueva partida en la BD.
    // $_SESSION['partida_id'] = PartidasRepository::crearPartida($_SESSION['user']->getId(), null);

    header('Location: index.php');
    die();
}

// 3. RECEPCIÓN Y VALIDACIÓN DE LA JUGADA
// Verificamos que el usuario ha enviado una jugada.
if (!isset($_POST['jugada'])) {
    // Si no hay jugada, no hacemos nada y volvemos al inicio.
    header('Location: index.php');
    die();
}

$jugadaUsuario = $_POST['jugada'];
$jugadasPermitidas = ['piedra', 'papel', 'tijera'];

// Validamos que la jugada sea una de las permitidas.
if (in_array($jugadaUsuario, $jugadasPermitidas)) {

    // 4. INTERACCIÓN CON EL MODELO
    // El controlador le pasa la jugada al modelo y este hace toda la lógica.
    // NOTA: Necesitarás crear el método `jugarRonda` en `PartidasRepository`.
    $resultadoRonda = PartidasRepository::jugarRonda($jugadaUsuario);

    // 5. GESTIÓN DEL ESTADO DE LA SESIÓN
    // Guardamos el resultado para mostrarlo en la vista.
    $_SESSION['ultima_jugada'] = [
        'jugada_usuario' => $jugadaUsuario,
        'jugada_maquina' => $resultadoRonda['maquina'],
        'resultado' => $resultadoRonda['resultado']
    ];

    // Actualizamos el marcador general del usuario.
    $user = $_SESSION['user'];
    $user->setTotalGames($user->getTotalGames() + 1);

    if ($resultadoRonda['resultado'] == 'victoria') {
        $user->setWins($user->getWins() + 1);
    } elseif ($resultadoRonda['resultado'] == 'derrota') {
        $user->setLosses($user->getLosses() + 1);
    }

    // Guardamos las estadísticas actualizadas en la base de datos.
    UsuariosRepository::actualizarEstadisticas($user);
}

// 6. REDIRECCIÓN A LA VISTA
// Redirigimos al `index.php`, que cargará la vista principal con los datos de la sesión.
header('Location: index.php');
die();