<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use GrahamCampbell\ResultType\Error;
use Model\Arbitros;
use Model\Partidos;
use Model\Perfiles;

class AccionesController
{

    public static function index(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        // Render a la vista 
        $router->render('admin/acciones/index', [
            'titulo' => 'Ajustes',
            'usuario' => $usuario
        ]);
    }

    public static function guardar_futbol(Router $router)
    {
        session_start();

        // Verifica que sea una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Configura el encabezado de respuesta como JSON
            header('Content-Type: application/json');

            // Limpia el buffer de salida si es necesario
            if (ob_get_level()) {
                ob_clean();
            }

            // Obtiene los datos enviados
            $data = json_decode(file_get_contents('php://input'), true);
            var_dump($data);
            exit;
            // Simula una respuesta exitosa
            echo json_encode([
                'success' => true,
                'message' => 'Restricción aplicada con éxito',
            ]);

            exit; // Detiene la ejecución
        }

        // Para cualquier solicitud que no sea POST
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido',
        ]);
        exit;
    }

    public static function guardar_sala(Router $router)
    {
        session_start();

        // Verifica que sea una solicitud POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Configura el encabezado de respuesta como JSON
            header('Content-Type: application/json');

            // Limpia el buffer de salida si es necesario
            if (ob_get_level()) {
                ob_clean();
            }

            // Obtiene los datos enviados
            $data = json_decode(file_get_contents('php://input'), true);
            var_dump($data);
            exit;
            // Simula una respuesta exitosa
            echo json_encode([
                'success' => true,
                'message' => 'Restricción aplicada con éxito',
            ]);

            exit; // Detiene la ejecución
        }

        // Para cualquier solicitud que no sea POST
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido',
        ]);
        exit;
    }
}
