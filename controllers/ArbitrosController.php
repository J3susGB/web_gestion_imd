<?php

namespace Controllers;

use MVC\Router;
use Model\Arbitros;
use Model\Perfiles;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Date;

class ArbitrosController
{

    public static function index(Router $router)
    {

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        $arbitros = Arbitros::all_apellidos_y_nombre();
        // debuguear($arbitros);

        foreach ($arbitros as $a) {
            if ($a->modalidad === "1") {
                $a->modalidad_nombre = "Fútbol";
            } else if ($a->modalidad === "2") {
                $a->modalidad_nombre = "Sala";
            }
        }

        foreach ($arbitros as $a) {
            if ($a->coche === "1") {
                $a->coche_nombre = "Si";
            } else if ($a->coche === "0") {
                $a->coche_nombre = "No";
            }
        }

        // debuguear($arbitros);


        // Render a la vista 
        $router->render('admin/arbitros/index', [
            'titulo' => 'Gestión de árbitros/as',
            'arbitros' => $arbitros,
            'usuario' => $usuario
        ]);
    }

    public static function carga_masiva(Router $router)
    {
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] === UPLOAD_ERR_OK) {
                $uploadsDir = __DIR__ . '/../uploads/';
                $fileName = basename($_FILES['excel_file']['name']);
                $filePath = $uploadsDir . $fileName;

                if (!is_dir($uploadsDir)) {
                    mkdir($uploadsDir, 0775, true);
                }

                if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $filePath)) {
                    try {
                        // Cargar el archivo Excel
                        $spreadsheet = IOFactory::load($filePath);
                        $sheet = $spreadsheet->getActiveSheet();

                        // Leer filas y columnas
                        $arbitros = [];
                        foreach ($sheet->getRowIterator() as $rowIndex => $row) {
                            // Ignorar la fila de encabezados
                            if ($rowIndex === 1) continue;

                            $cellIterator = $row->getCellIterator();
                            $cellIterator->setIterateOnlyExistingCells(false); // También incluye celdas vacías

                            // Asignar valores a variables
                            $data = [];
                            foreach ($cellIterator as $colIndex => $cell) {
                                $data[] = $cell->getValue(); // Obtén el valor de la celda
                            }

                            // Asignar las celdas a variables 
                            list($modalidad, $apellido1, $apellido2, $nombre, $codigo_postal, $telefono, $email, $coche) = $data;

                            // // Validar y convertir la fecha
                            // $fecha_formato = null;
                            // if (is_numeric($fecha)) {
                            //     $fecha_formato = Date::excelToDateTimeObject($fecha)->format('d/m/Y'); // Convertir a día/mes/año
                            // } else {
                            //     $fecha_formato = "Fecha inválida"; // O asigna otro valor predeterminado
                            // }

                            $arbitros[] = [
                                'modalidad' => $modalidad,
                                'apellido1' => $apellido1,
                                'apellido2' => $apellido2,
                                'nombre' => $nombre,
                                'codigo_postal' => $codigo_postal,
                                'telefono' => $telefono,
                                'email' => $email,
                                'coche' => $coche
                            ];
                        }

                        // Imprime los partidos para verificar
                        // echo '<pre>';
                        // print_r($arbitros);
                        // echo '</pre>';

                        // AQUI INSTANCIAR MODELO ARIBTROS
                        $arbitros_pasados = new Arbitros();
                        // debuguear($partidos_pasados);

                        foreach ($arbitros as $p) {
                            $arbitros_pasados->modalidad = $p['modalidad'];
                            $arbitros_pasados->nombre = $p['nombre'];
                            $arbitros_pasados->apellido1 = $p['apellido1'];
                            $arbitros_pasados->apellido2 = $p['apellido2'];
                            $arbitros_pasados->codigo_postal = $p['codigo_postal'];
                            $arbitros_pasados->telefono = $p['telefono'];
                            $arbitros_pasados->email = $p['email'];
                            $arbitros_pasados->coche = $p['coche'];

                            // $partidos_pasados->designado = isset($p['designado']) ? (int)$p['designado'] : 0; // Asegurar entero

                            $resultado = $arbitros_pasados->guardar();

                            if ($resultado) {
                                header('Location: /admin/arbitros');
                            }
                        }
                    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                        echo "Error al leer el archivo: " . $e->getMessage();
                    }
                } else {
                    echo "Error: No se pudo mover el archivo al directorio de subida.";
                }
            } else {
                echo "Error: No se ha seleccionado ningún archivo o ocurrió un problema con la subida.";
            }
        }

        // Render a la vista 
        $router->render('/admin/arbitros/carga_masiva', [
            'titulo' => 'Carga masiva de árbitros/as',
            'usuario' => $usuario
        ]);
    }

    public static function truncate(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $arbitros = new Arbitros();
            // debuguear($arbitros);

            $resultado = $arbitros->truncate('arbitros');

            if ($resultado) {
                header('Location: /admin/dashboard');
            }
        }
    }

    public static function agregar(Router $router)
    {

        $alertas = [];

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        // Inicializar la variable $arbitro
        $arbitro = new Arbitros(); // Crear una instancia vacía para la vista

        // Comprobar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // debuguear($_POST);

            // Recoger los datos enviados
            $arbitro = new Arbitros($_POST);

            //Asignamos valores que faltan a la instancia arbitro desde post
            $arbitro->codigo_postal = $_POST['cp'];
            $arbitro->coche = (int)$_POST['coche'];
            $arbitro->modalidad = (int)$_POST['deporte'];

            $arbitro->coche = isset($_POST['coche']) ? (int)$_POST['coche'] : null; // Asignar null si no está seleccionado
            $arbitro->modalidad = isset($_POST['deporte']) ? (int)$_POST['deporte'] : null;


            // debuguear($arbitro);

            // Validar los datos (debes crear el método validar en tu modelo)
            $alertas = $arbitro->validar_arbitro();
            // debuguear($alertas);

            // Si no hay errores, guardar
            if (empty($alertas)) {

                $existeArbitro = Arbitros::where('email', $arbitro->email);

                if ($existeArbitro) {
                    Arbitros::setAlerta('error', 'El arbitro ya esta registrado');
                    $alertas = Arbitros::getAlertas();
                } else {
                    $resultado = $arbitro->guardar();
                    if ($resultado) {
                        sleep(2);
                        // Redireccionar a una página de éxito o lista
                        header('Location: /admin/arbitros');
                    }
                }
            }
        }

        // Render a la vista 
        $router->render('admin/arbitros/agregar', [
            'titulo' => 'Agregar árbitro/a',
            'alertas' => $alertas,
            'arbitro' => $arbitro,
            'usuario' => $usuario
        ]);
    }

    public static function editar(Router $router)
    {

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

        // Obtener el miembro a editar:
        $arbitro = Arbitros::find($id);
        //  debuguear($arbitro);

        // debuguear($modalidad);

        if (!$arbitro) {
            desconectar();
        }

        // Comprobar si se ha enviado el formulario
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_GET['id']; // Leer el id de la url
            $id = filter_var($id, FILTER_VALIDATE_INT); // Validar que este id siempre sea un número entero

            // debuguear($_POST);

            // Recoger los datos enviados
            $arbitro = Arbitros::find($id);
            // debuguear($arbitro);

            $arbitro->sincronizar($_POST);
            // debuguear($arbitro);

            if (!$arbitro) {
                desconectar();
            }

            // debuguear($arbitro);

            // Validar los datos
            $alertas = $arbitro->validar_arbitro();
            // debuguear($alertas);

            // Si no hay errores, guardar
            if (empty($alertas)) {
                
                $resultado = $arbitro->guardar();
                if ($resultado) {
                    sleep(2);
                    // Redireccionar a una página de éxito o lista
                    header('Location: /admin/arbitros');
                }
            }
        }

        // Render a la vista 
        $router->render('admin/arbitros/editar', [
            'titulo' => 'Editar árbitro/a',
            'alertas' => $alertas,
            'arbitro' => $arbitro,
            'usuario' => $usuario
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

            $arbitro = Arbitros::find($id);

            if (!$arbitro) {
                echo json_encode(['success' => false, 'message' => 'Elemento no encontrado']);
                exit;
            }

            $resultado = $arbitro->eliminar();

            if ($resultado) {
                echo json_encode(['success' => true]);
                header('location: /admin/arbitros');
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar']);
            }
            exit;
        }
    }

    public static function actualizar_estado()
    {
        // Verifica que la solicitud sea POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json'); // Asegura que se devuelva JSON

            ob_clean(); // Limpia cualquier salida previa

            $data = json_decode(file_get_contents('php://input'), true);

            $id = $data['id'];
            $activo = $data['activo'];

            error_log("ID recibido: $id, Activo: $activo");

            // Busca el árbitro por ID y actualiza el campo `activo`
            $arbitro = Arbitros::find($id);
            if ($arbitro) {
                $arbitro->activo = $activo;

                error_log("Antes de guardar: " . json_encode($arbitro));

                $resultado = $arbitro->guardar(); // Método para guardar cambios en la base de datos

                error_log("Resultado de guardar: " . ($resultado ? 'true' : 'false'));

                error_log(json_encode(['success' => $resultado])); // Verifica el JSON antes de enviarlo
                echo json_encode(['success' => $resultado]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }
}
