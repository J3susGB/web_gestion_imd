<?php

use Model\Perfiles;

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function desconectar()
{
    $_SESSION = array();

    // Elimina la cookie de sesión (opcional)
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destruye la sesión
    session_destroy();
    header('Location: /');
}

// Funcion para obtener nombre arbitro
function obtenerNombreArbitro($partido, $arbitros) {
    foreach ($arbitros as $a) {
        if ($partido->id_arbitro && $partido->id_arbitro == $a->id) {
            return $a->apellido1 . " " . $a->apellido2 . ", " . $a->nombre;
        }
    }
    return "Pendiente designar";
}

// Funcion para obtener nombre categoria
function obtenerNombreCategoria($partido, $categorias) {
    foreach ($categorias as $a) {
        if($partido->modalidad == 1 && $a->id_modalidad == 1) {
            if ($partido->categoria && $partido->categoria == $a->nombre2) {
                return $a->nombre;
            }
        } else if($partido->modalidad == 2 && $a->id_modalidad == 2){
            if ($partido->categoria && $partido->categoria == $a->nombre2) {
                return $a->nombre;
            }
        }
    }
    return null;
}

// Funcion para obtener nombre arbitro
function obtenerNombreModalidad($partido, $modalidad) {
    foreach ($modalidad as $a) {
        if ($partido->modalidad && $partido->modalidad == $a->id) {
            return $a->nombre;
        }
    }
    return null;
}

function is_admin() : bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] === '1';
    // error_log("is_admin: " . ($is_admin ? 'true' : 'false')); // Para depuración
    return $is_admin;
}

