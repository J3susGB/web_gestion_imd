<?php

namespace Controllers;

use Classes\Email;
use Model\Perfiles;
use MVC\Router;

class PerfilesController
{
    public static function index(Router $router)
    {
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        $perfiles = Perfiles::all_apellido1('ASC');
        // debuguear($perfiles);

        // Render a la vista 
        $router->render('admin/perfiles/index', [
            'titulo' => 'Usuarios',
            'usuario' => $usuario,
            'perfiles' => $perfiles
        ]);
    }

    public static function actualizar_estado()
    {
        // Verifica que la solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);

            $id = $data['id'];
            $activo = $data['activo'];

            // Busca el perfil por ID y actualiza el campo `activo`
            $perfil = Perfiles::find($id);
            if ($perfil) {
                $perfil->activo = $activo;
                $resultado = $perfil->guardar(); // Método para guardar cambios en la base de datos

                echo json_encode(['success' => $resultado]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public static function agregar(Router $router)
    {

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        $alertas = [];
        $user = new Perfiles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // debuguear($_POST);
            $user = new Perfiles($_POST);
            // debuguear($user);

            //Validar el perfil
            $alertas = $user->validar_perfil();
            // debuguear($alertas);

            if (empty($alertas)) {

                // Hashear el password
                $user->hashPassword();
                // debuguear($user);

                //Guardamos en la BD
                $resultado = $user->guardar();
                // debuguear($resultado);

                if ($resultado) {
                    sleep(2);
                    // Redireccionar a una página de éxito o lista
                    header('Location: /admin/perfiles');
                }
            }
        }

        // Render a la vista 
        $router->render('admin/perfiles/agregar', [
            'titulo' => 'Agregar Usuario',
            'usuario' => $usuario,
            'alertas' => $alertas,
            'user' => $user
        ]);
    }

    public static function editar(Router $router)
    {

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        $alertas = [];
        $user = new Perfiles();

        // Validar el id:
        $id = $_GET['id']; // Leer el id de la url
        $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que este id siempre sea un número entero

        //  debuguear($id);

        if (!$id) {
            desconectar();
        }

        // Obtener el miembro a editar:
        $user = Perfiles::find($id);
        //  debuguear($user);

        if (!$user) {
            desconectar();
        }

        // Comprobar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_GET['id']; // Leer el id de la url
            $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que este id siempre sea un número entero

            // debuguear($_POST);

            // Recoger los datos enviados
            $user = Perfiles::find($id);
            // debuguear($user);

            $user->sincronizar($_POST);
            // debuguear($user);

            if (!$user) {
                desconectar();
            }

            // debuguear($arbitro);

            // Validar los datos
            $alertas = $user->validar_perfil();
            // debuguear($alertas);

            // Si no hay errores, guardar
            if (empty($alertas)) {

                // // Hashear el password
                // $user->hashPassword();
                // // debuguear($user);

                $resultado = $user->guardar();
                if ($resultado) {
                    sleep(2);
                    // Redireccionar a una página de éxito o lista
                    header('Location: /admin/perfiles');
                }
            }
        }

        // Render a la vista 
        $router->render('admin/perfiles/editar', [
            'titulo' => 'Editar Usuario',
            'usuario' => $usuario,
            'alertas' => $alertas,
            'user' => $user
        ]);
    }

    public static function eliminar(Router $router)
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;
            $id = filter_var($id, FILTER_VALIDATE_INT);

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'ID inválido']);
                exit;
            }

            $user = Perfiles::find($id);

            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'Elemento no encontrado']);
                exit;
            }

            $resultado = $user->eliminar();

            if ($resultado) {
                echo json_encode(['success' => true]);
                header('location: /admin/perfiles');
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar']);
            }
            exit;
        }
    }

    public static function restablecer_password()
    {
        // Verifica que la solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Accede a los datos enviados a través del formulario (no JSON)
            $id = $_POST['id'];
            $activo = isset($_POST['activo']) ? $_POST['activo'] : 0; // Define un valor predeterminado si no se recibe 'activo'

            // Busca el perfil por ID
            $perfil = Perfiles::find($id);

            if ($perfil) {

                // Enviar el email
                $email = new Email([
                    'email' => $perfil->email,
                    'nombre' => $perfil->nombre
                ]);

                $resultado = $email->enviarInstrucciones();

                echo json_encode(['success' => $resultado]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }


    public static function restablecer(Router $router)
    {

        $email = s($_GET['email']);

        $email_valido = true;

        if (!$email) header('Location: /');

        // Identificar el usuario con este email
        $usuario = Perfiles::where('email', $email);

        if (empty($usuario)) {
            Perfiles::setAlerta('error', 'No Válido, inténtalo de nuevo');
            $email_valido = false;
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // debuguear($_POST);

            //Comprobar que el usuario coincide con el almacenado en BD
            $email = s($_GET['email']);
            $usuario = Perfiles::where('email', $email);
            // debuguear($email);
            // debuguear($usuario);

            if ($usuario->usuario !== $_POST['user']) {
                Perfiles::setAlerta('error', 'El usuario no existe');

            } else {

                // Añadir el nuevo password
                $usuario->sincronizar($_POST);
                // debuguear($usuario);

                if (!$usuario->password2) {
                    $usuario->password2 = $_POST['password2'];
                }

                // debuguear($usuario);

                // Validar el password
                $alertas = $usuario->validar_reseteo();
                // debuguear($alertas);

                if (empty($alertas)) {
                    // Hashear el nuevo password
                    $usuario->hashPassword();

                    // debuguear($usuario);

                    //Eliminar el campo password2
                    unset($usuario->password2);
                    // debuguear($usuario);

                    // Guardar el usuario en la BD
                    $resultado = $usuario->guardar();

                    // Redireccionar
                    if ($resultado) {
                        sleep(2);
                        header('Location: /');
                    }
                }
            }
        }

        $alertas = Perfiles::getAlertas();

        // Muestra la vista
        $router->render('admin/perfiles/restablecer', [
            'titulo' => 'Crear nueva contraseña',
            'alertas' => $alertas,
            'token_valido' => $email_valido
        ]);
    }
}
