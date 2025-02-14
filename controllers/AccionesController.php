<?php

namespace Controllers;

use MVC\Router;
use Model\Perfiles;
use Model\Restricciones;

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

        //Traemos restricciones
        $restricciones = Restricciones::all();

        // Render a la vista 
        $router->render('admin/acciones/index', [
            'titulo' => 'Ajustes',
            'usuario' => $usuario,
            'restricciones' => $restricciones
        ]);
    }

    public static function guardar_futbol(Router $router)
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            if (ob_get_level()) {
                ob_clean();
            }

            // Obtener datos enviados
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos recibidos
            if (!isset($data['maxSXJX']) || !isset($data['maxPartidos'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos incompletos'
                ]);
                exit;
            }

            // Convertir valores a enteros para evitar errores
            $maxSXJX = (int)$data['maxSXJX'];
            $maxPartidos = (int)$data['maxPartidos'];

            // Obtener todas las restricciones
            $restricciones = Restricciones::all();
            $modalidad = 1;
            $restriccionExistente = null;

            // Buscar si ya existe una restricción para esta modalidad
            foreach ($restricciones as $r) {
                if ($r->modalidad == $modalidad) {
                    $restriccionExistente = $r;
                    break;
                }
            }

            if (!$restriccionExistente) {
                // Si no existe una restricción para esta modalidad, la creamos
                $restriccion = new Restricciones();
                $restriccion->modalidad = $modalidad;
            } else {
                // Si ya existe, reutilizamos la restricción encontrada
                $restriccion = $restriccionExistente;
            }

            // Asignamos valores
            $restriccion->numero_partidos = $maxPartidos;
            $restriccion->numero_metalico = $maxSXJX;
            $restriccion->activo = ($maxPartidos > 0 && $maxSXJX > 0) ? 1 : 0; // Activo solo si ambos valores son > 0

            // Guardar en la base de datos
            $resultado = $restriccion->guardar();

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Restricción aplicada con éxito'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Hubo un problema y la restricción no se aplicó'
                ]);
            }

            exit;
        }

        // Si la solicitud no es POST
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            if (ob_get_level()) {
                ob_clean();
            }

            // Obtener datos enviados
            $data = json_decode(file_get_contents('php://input'), true);

            // Validar datos
            if (!isset($data['maxSXJX']) || !isset($data['maxPartidos'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos incompletos'
                ]);
                exit;
            }

            $maxSXJX = (int)$data['maxSXJX'];
            $maxPartidos = (int)$data['maxPartidos'];

            // Obtener todas las restricciones
            $restricciones = Restricciones::all();
            $modalidad = 2;
            $restriccionExistente = null;

            // Buscar si ya existe una restricción para esta modalidad
            foreach ($restricciones as $r) {
                if ($r->modalidad == $modalidad) {
                    $restriccionExistente = $r;
                    break;
                }
            }

            if (!$restriccionExistente) {
                // Si no hay restricción para esta modalidad, la creamos
                $restriccion = new Restricciones();
                $restriccion->modalidad = $modalidad;
            } else {
                // Si ya existe, reutilizamos la restricción encontrada
                $restriccion = $restriccionExistente;
            }

            // Asignamos valores
            $restriccion->numero_partidos = $maxPartidos;
            $restriccion->numero_metalico = $maxSXJX;
            $restriccion->activo = ($maxPartidos > 0 && $maxSXJX > 0) ? 1 : 0; // Activo solo si ambos valores son > 0

            // Guardar en la base de datos
            $resultado = $restriccion->guardar();

            if ($resultado) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Restricción aplicada con éxito'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Hubo un problema y la restricción no se aplicó'
                ]);
            }

            exit;
        }

        // Si la solicitud no es POST
        header('HTTP/1.1 405 Method Not Allowed');
        echo json_encode([
            'success' => false,
            'message' => 'Método no permitido',
        ]);
        exit;
    }
}
