<?php

// --- MOSTRAR VISTAS (Registro / Editar Perfil) ---
if (isset($_GET['action']) && $_GET['action'] == 'register') {
    require_once 'views/registerView.phtml';
    die();
}


// --- ACCIÓN DE CERRAR SESIÓN ---
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    die();
}

// --- ACCIÓN DE REGISTRO ---
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    if ($password !== $password2) {
        $_SESSION['error_message'] = "Las contraseñas no coinciden.";
        header('Location: index.php?c=usuarios&action=register');
        die();
    }

    // 1. Comprobamos si el nombre de usuario ya existe
    $existingUser = UsuariosRepository::obtenerPorNombre($username);

    if ($existingUser) {
        // Si el usuario ya existe, guardamos un error y redirigimos
        $_SESSION['error_message'] = "El nombre de usuario '$username' ya está en uso.";
        header('Location: index.php?c=usuarios&action=register');
        die();
    } else {
        // 2. Si no existe, procedemos con el registro
        $newUser = new Usuarios(null, $username, 'default.png');
        $userId = UsuariosRepository::registrar($newUser, $password);

        if ($userId) {
            // Si el registro es exitoso, iniciamos sesión automáticamente
            $_SESSION['user'] = UsuariosRepository::obtenerPorId($userId);
        }
    }

    header('Location: index.php');
    die();
}

// --- ACCIÓN DE INICIO DE SESIÓN ---
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = UsuariosRepository::iniciarSesion($username, $password);

    if ($user) {
        $_SESSION['user'] = $user;
    } else {
        // Si el login falla, guardamos un mensaje de error
        $_SESSION['error_message'] = "Usuario o contraseña incorrectos.";
    }
    header('Location: index.php');
    die();
}

// --- ACCIÓN DE MOSTRAR VISTA DE EDITAR PERFIL ---
if (isset($_GET['edit'])) {
    require_once 'views/editProfileView.phtml';
    die();
}
// --- ACCIÓN DE EDITAR PERFIL (subida de avatar) ---
if (isset($_POST['edit_profile'])) {
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $fileName = time() . '_' . basename($_FILES["avatar"]["name"]);
        move_uploaded_file($_FILES['avatar']['tmp_name'], 'public/img/' . $fileName);
        
        UsuariosRepository::actualizarAvatar($fileName, $_SESSION['user']);
        $_SESSION['user']->setAvatar($fileName); // Actualizamos el objeto en sesión
    }
    header('Location: index.php');
    die();
}