<?php

// 1. Inclusión de dependencias: Carga todas las clases de Modelos y Repositorios
//    necesarias para el funcionamiento del juego.
require_once('models/Usuarios.php');
require_once('models/UsuariosRepository.php');
require_once('models/PartidasRepository.php'); 
require_once('models/Partidas.php');


// 2. Inicialización de sesiones: Inicializa las sesiones en el servidor
// Necesita las sessiones de PHP para poder gestionar
//    la información del usuario (`$_SESSION`).
session_start();

// 3. Enrutamiento (Routing): Comprueba si se ha pasado un parámetro 'c' (controlador)
//    en la URL. Si es así, delega la petición al controlador correspondiente
//    (ej: userController.php, threadController.php) y ese controlador se encargará
//    de la petición.
if (isset($_GET['c'])) {
    require_once('controllers/' . $_GET['c'] . 'Controller.php');
    // Detenemos la ejecución aquí para que el controlador principal no interfiera.
    die();
}

// 4. Autenticación: Si no se ha delegado a otro controlador, verifica si el usuario
//    ha iniciado sesión. Si no lo ha hecho, muestra la vista de login y detiene la ejecución.
if (!isset($_SESSION['user'])) {
    require_once 'views/loginView.phtml';
    die();
}

// 5. Carga de datos y renderizado de la vista principal: Si el usuario SÍ ha iniciado sesión,
//    simplemente se carga la vista principal del juego. La vista se encargará de mostrar
//    los datos de la sesión (marcador, última jugada, etc.).
require_once 'views/mainView.phtml';