<?php

namespace Controllers;

use Model\Categorias;
use Model\Designaciones;
use Model\Modalidades;
use Model\Partidos;
use Model\Perfiles;
use MVC\Router;

class CategoriasController
{

    public static function index(Router $router)
    {

        //Traemos todas las categorias
        $categorias = Categorias::all('ASC');
        // debuguear($categorias);

        //Traemos todas las modalidades
        $modalidades = Modalidades::all();

        // Render a la vista 
        $router->render('admin/categorias/index', [
            'titulo' => 'Gestión de Categorías',
            'categorias' => $categorias,
            'modalidades' => $modalidades
        ]);
    }

    public static function agregar(Router $router)
    {

        //Inicializamos alertas
        $alertas = [];

        //Inicializamos modelo categorias
        $categoria = new Categorias();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // debuguear($_POST);

            //Transformamos tipos de datos del post para que concuerden con base datos
            $_POST['tarifa'] = (float)$_POST['tarifa'];
            $_POST['facturar'] = (float)$_POST['facturar'];
            $_POST['pago_arbitro'] = (float)$_POST['pago_arbitro'];
            $_POST['oa'] = (float)$_POST['oa'];
            $_POST['id_modalidad'] = (int)$_POST['id_modalidad'];

            // debuguear($_POST);

            //Creamos objeto categorias y sincornizamos con el post
            $categoria = new Categorias($_POST);

            // debuguear($categoria);

            //Validación
            $alertas = $categoria->validar_categoria();
            // debuguear($alertas);

            if (empty($alertas)) {
                //Guardamos en base de datos
                $resultado = $categoria->guardar();

                if ($resultado) {
                    sleep(2);
                    header('Location: /admin/categorias');
                }
            }
        }

        // Render a la vista 
        $router->render('admin/categorias/agregar', [
            'titulo' => 'Agregar Categoría',
            'alertas' => $alertas,
            'categoria' => $categoria
        ]);
    }

    public static function editar(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        $alertas = [];

        // Validar el id:
        $id = $_GET['id']; // Leer el id de la url
        $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que este id siempre sea un número entero

        //  debuguear($id);

        if (!$id) {
            desconectar();
        }

        // Obtener la categoría a editar:
        $categoria = Categorias::find($id);
        //  debuguear($arbitro);

        // debuguear($modalidad);

        if (!$categoria) {
            desconectar();
        }

        // debuguear($partido);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_admin()) {
                header('Location: /');
            }

            $id = $_GET['id']; // Leer el id de la url
            $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que este id siempre sea un número entero

            //  debuguear($id);

            // debuguear($_POST);

            if (!$id) {
                desconectar();
            }

            // Obtener el partido a editar:
            $categoria = Categorias::find($id);
            // debuguear($categoria);

            //Sincronizar con el post:
            $categoria->sincronizar($_POST);

            // debuguear($partido);

            // Validar los datos
            $alertas = $categoria->validar_categoria();
            // debuguear($alertas);

            // Si no hay errores, guardar y enviar email con el cambio si está enviado o aceptado (estado 2 o 3)
            if (empty($alertas)) {

                $resultado = $categoria->guardar();

                if ($resultado) {
                    sleep(2);
                    // Redireccionar a una página de éxito o lista
                    header('Location: /admin/categorias');
                }
            }
        }

        // Render a la vista 
        $router->render('admin/categorias/editar', [
            'titulo' => 'Editar Categoría',
            'alertas' => $alertas,
            'usuario' => $usuario,
            'categoria' => $categoria
        ]);
    }

    public static function eliminar(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_admin()) {
                header('Location: /');
            }

            // Obtener el ID del partido desde los parámetros de la URL
            $id = $_GET['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID inválido']);
                exit;
            }

            // Buscar el partido
            $categoria = Categorias::find($id);

            if (!$categoria) {
                echo json_encode(['success' => false, 'message' => 'Elemento no encontrado']);
                exit;
            }

            // Eliminar el partido y la designación
            $resultado = $categoria->eliminar();

            if ($resultado) {

                // Comprobar si el correo se envió correctamente
                if ($resultado) {
                    echo json_encode(['success' => true]);
                    exit; // Detener la ejecución después de enviar la respuesta
                }
            }
        }
    }
}
