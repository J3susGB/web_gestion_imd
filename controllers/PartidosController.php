<?php

namespace Controllers;

use Classes\Email;
use Exception;
use Model\Arbitros;
use MVC\Router;
use Model\Partidos;
use Model\Perfiles;
use Model\Modalidad;
use Model\Categorias;
use Model\Designaciones;
use Model\Modalidades;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PartidosController
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

        $partidos = Partidos::all('ASC');
        // debuguear($partidos);
        $categorias = Categorias::all('ASC');
        // debuguear($categorias);

        $designaciones = Designaciones::all('ASC');

        //Array para almacenar las fechas de forma dinámica por jornada
        $fechas = [];

        foreach ($partidos as $p) {
            $fechas[] = $p->fecha;
        }

        // Eliminar fechas duplicadas
        $fechasUnicas = array_unique($fechas);

        // Ordenar las fechas
        sort($fechasUnicas);

        // Crear array asociativo para añadir id
        $fechasAsociativo = [];
        foreach ($fechasUnicas as $index => $fecha) {
            $fechasAsociativo[] = [
                'id' => $index,
                'fecha' => $fecha
            ];
        }

        // debuguear($fechasAsociativo);

        //Array para almacenar los distrito de forma dinámica por jornada
        $distritos = [];

        foreach ($partidos as $p) {
            $distritos[] = $p->distrito;
        }

        // Eliminar distritos duplicadas
        $distritosUnicas = array_unique($distritos);

        // Ordenar las distritos
        sort($distritosUnicas);

        // Crear array asociativo para añadir id
        $distritosAsociativo = [];
        foreach ($distritosUnicas as $index => $distrito) {
            $distritosAsociativo[] = [
                'id' => $index,
                'distrito' => $distrito
            ];
        }
        // debuguear($distritosAsociativo);

        $arbitros = Arbitros::all_apellidos_y_nombre('ASC');
        // debuguear($arbitros);

        //Traemos todos los perfiles
        $perfiles = Perfiles::all();

        // Capturar filtros si vienen por GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

            if (!is_admin()) {
                header('Location: /');
            }

            $filtros = $_GET;

            // Traemos todos los partidos, árbitros, categorías y modalidades
            $partidos_filtro = Partidos::all('ASC');
            $arbis = Arbitros::all_apellidos_y_nombre();
            $cate = Categorias::all('ASC');
            $mod = Modalidades::all('ASC');

            // Cruzamos datos de partidos y árbitros para añadir el nombre y apellidos de los árbitros a los partidos
            foreach ($partidos_filtro as $p) {
                $p->nombre_arbitro = obtenerNombreArbitro($p, $arbis);
            }

            // Cruzamos datos de partidos y categorías
            foreach ($partidos_filtro as $p) {
                $p->nombre_categoria = obtenerNombreCategoria($p, $cate);
            }

            // Cruzamos datos de partidos y modalidades
            foreach ($partidos_filtro as $p) {
                $p->nombre_modalidad = obtenerNombreModalidad($p, $mod);
            }

            // Aplicar filtros
            $partidos_filtro = array_filter($partidos_filtro, function ($partido) use ($filtros, $fechasAsociativo, $distritosAsociativo) {

                // Filtro por Árbitro
                if (!empty($filtros['arbitro']) && stripos(trim($partido->nombre_arbitro), trim($filtros['arbitro'])) === false) {
                    return false;
                }

                // Filtro por ID Partido
                if (!empty($filtros['id_partido']) && $partido->id_partido != $filtros['id_partido']) {
                    return false;
                }

                // Filtro por Campo
                if (!empty($filtros['campo']) && stripos(trim($partido->terreno), trim($filtros['campo'])) === false) {
                    return false;
                }

                // Filtro por Equipo (Local o Visitante)
                if (!empty($filtros['equipo']) && stripos(trim($partido->local), trim($filtros['equipo'])) === false && stripos(trim($partido->visitante), trim($filtros['equipo'])) === false) {
                    return false;
                }

                // Filtro por Categoría
                if (!empty($filtros['categoria']) && $partido->nombre_categoria != $filtros['categoria']) {
                    return false;
                }

                // Filtro por Fecha
                if (!empty($filtros['fecha']) && $partido->fecha != $filtros['fecha']) {
                    return false;
                }

                // Filtro por Distrito
                if (!empty($filtros['distrito']) && $partido->distrito != $filtros['distrito']) {
                    return false;
                }

                // // Filtro por Estado
                // if (!empty($filtros['estado']) && (string)$partido->estado !== (string)$filtros['estado']) {
                //     return false;
                // }
                // Filtro por Estado
                if (isset($filtros['estado']) && $filtros['estado'] !== '' && (string)$partido->estado !== (string)$filtros['estado']) {
                    return false;
                }


                // Filtro por Deporte (Modalidad)
                if (!empty($filtros['deporte']) && strtolower(trim($partido->nombre_modalidad)) != strtolower(trim($filtros['deporte']))) {
                    return false;
                }

                return true;
            });

            // Garantizar que $partidos nunca sea null
            $partidos = !empty($partidos_filtro) ? $partidos_filtro : [];
            $sinResultados = empty($partidos_filtro); // Variable para detectar si no hay resultados
        } else {
            // Si no hay filtros aplicados, mostrar todos los partidos
            $partidos = Partidos::all('ASC');
            $sinResultados = false; // No hay búsqueda, por lo tanto no hay "sin resultados"
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_admin()) {
                header('Location: /');
            }

            // debuguear($_POST);

            $envio_partidos = $_POST['seleccionar'];
            // debuguear($envio_partidos);

            //Nos traemos todos los partidos
            $partidos = Partidos::all_orden_fecha();
            // debuguear($partidos);

            foreach ($partidos as $p) {
                foreach ($envio_partidos as $id_partido) {
                    if ($p->id_partido == $id_partido) {
                        //Traemos partido y designación
                        $partido = Partidos::encuentra_partido($id_partido);
                        $designacion = Designaciones::encuentra_partido($id_partido);

                        //Traemos todos los arbitros
                        $arbitros = Arbitros::all();

                        //Cruzamos datos de designacion y arbitros
                        foreach ($arbitros as $a) {
                            if ($a->id == $designacion->id_arbitro) {

                                //Cambiamos estados a ambos a enviado
                                $partido->estado = 2;
                                $designacion->estado = 2;

                                //Añadimos campos facturar, tarifa, pago_arbitro y oa a designacion
                                $categorias = Categorias::all();
                                foreach($categorias as $c) {
                                    if($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                                        $designacion->tarifa = $c->tarifa;
                                        $designacion->facturar = $c->facturar;
                                        $designacion->pago_arbitro = $c->pago_arbitro;
                                        $designacion->oa = $c->oa;
                                    }

                                    //Lo mismo pero para delegados de campo
                                    if($designacion->categoria == $c->nombre && $designacion->modalidad == $c->id_modalidad) {
                                        $designacion->tarifa = $c->tarifa;
                                        $designacion->facturar = $c->facturar;
                                        $designacion->pago_arbitro = $c->pago_arbitro;
                                        $designacion->oa = $c->oa;
                                    }
                                }

                                //Guardamos cambios en la BD
                                $resultado = $partido->guardar();
                                $resultado2 = $designacion->guardar();

                                if ($resultado && $resultado2) {
                                    //Enviamos designaciones por correo
                                    $email = new Email([
                                        'id' => $designacion->id,
                                        'id_partido' => $designacion->id_partido,
                                        'email' => $a->email,
                                        'apellido1' => $a->apellido1,
                                        'apellido2' => $a->apellido2,
                                        'nombre' => $a->nombre,
                                        'fecha' => $designacion->fecha,
                                        'hora' => $designacion->hora,
                                        'terreno' => $designacion->terreno,
                                        'categoria' => $designacion->categoria,
                                        'grupo' => $designacion->grupo,
                                        'local' => $designacion->local,
                                        'visitante' => $designacion->visitante,
                                        'observaciones' => $designacion->observaciones
                                    ]);

                                    $email->enviar_partido();
                                    header('Location: /admin/partidos');
                                }
                            }
                        }
                    }
                }
            }
        }

        // Render a la vista 
        $router->render('admin/partidos/index', [
            'titulo' => 'Gestión de partidos',
            'partidos' => $partidos,
            'categorias' => $categorias,
            'fechasAsociativo' => $fechasAsociativo,
            'distritosAsociativo' => $distritosAsociativo,
            'usuario' => $usuario,
            'arbitros' => $arbitros,
            'designaciones' => $designaciones,
            'filtros' => $_GET ?? [],
            'sinResultados' => $sinResultados,
            'perfiles' => $perfiles
        ]);
    }

    public static function subir(Router $router)
    {
        if (!is_admin()) {
            header('Location: /');
        }

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_admin()) {
                header('Location: /');
            }

            session_start();
            // debuguear($_SESSION);
            $usuario = new Perfiles($_SESSION);
            // debuguear($usuario);

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
                        $partidos = [];
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

                            // Asignar las celdas a variables (ajusta según tus columnas)
                            list($modalidad, $cat, $nom_cat, $grupo, $idPartido, $fecha, $hora, $terreno, $distrito, $jornada, $local, $visitante) = $data;

                            // Validar y convertir la fecha
                            $fecha_formato = null;
                            if (is_numeric($fecha)) {
                                $fecha_formato = Date::excelToDateTimeObject($fecha)->format('d/m/Y'); // Convertir a día/mes/año
                            } else {
                                $fecha_formato = "Fecha inválida"; // O asigna otro valor predeterminado
                            }

                            $partidos[] = [
                                'modalidad' => (int)$modalidad,
                                'categoria' => $cat,
                                'nombre_categoria' => $nom_cat,
                                'grupo' => $grupo,
                                'id' => $idPartido,
                                'fecha' => $fecha_formato,
                                'hora' => $hora,
                                'terreno' => $terreno,
                                'distrito' => $distrito,
                                'jornada' => $jornada,
                                'local' => $local,
                                'visitante' => $visitante
                            ];
                        }

                        // Imprime los partidos para verificar
                        // echo '<pre>';
                        // print_r($partidos);
                        // echo '</pre>';

                        // debuguear($partidos);

                        // AQUI INSTANCIAR MODELO PARTIDOS
                        $partidos_pasados = new Partidos();
                        // debuguear($partidos_pasados);

                        //Instaciar Modelo modalidad para cruzar datos
                        // $modalidades = Modalidad::all();
                        // debuguear($modalidades);

                        foreach ($partidos as $p) {
                            // $partidos_pasados->id_usuario = (int)$usuario['id'];
                            // $partidos_pasados->id_arbitro = null;
                            $partidos_pasados->id_partido = $p['id'];
                            $partidos_pasados->categoria = $p['categoria'];
                            $partidos_pasados->grupo = $p['grupo'];
                            $partidos_pasados->fecha = $p['fecha'];
                            $partidos_pasados->hora = $p['hora'];
                            $partidos_pasados->terreno = $p['terreno'];
                            $partidos_pasados->distrito = $p['distrito'];
                            $partidos_pasados->jornada = $p['jornada'];
                            $partidos_pasados->local = $p['local'];
                            $partidos_pasados->visitante = $p['visitante'];
                            $partidos_pasados->observaciones = $p['observaciones'] ?? '';
                            $partidos_pasados->estado = 0;
                            $partidos_pasados->modalidad = (int)$p['modalidad'];

                            // debuguear($partidos_pasados);

                            $resultado = $partidos_pasados->guardar();
                            // debuguear($resultado);

                            if ($resultado) {
                                header('Location: /admin/partidos');
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
        $router->render('admin/partidos/subir_partidos', [
            'titulo' => 'Carga masiva de partidos',
            'usuario' => $usuario
        ]);
    }

    public static function truncate(Router $router)
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_admin()) {
                header('Location: /');
            }

            $partidos = new Partidos();
            // debuguear($partidos);

            $resultado = $partidos->truncate('partidos');

            if ($resultado) {
                header('Location: /admin/dashboard');
            }
        }
    }

    public static function agregar(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        $partido = new Partidos();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!is_admin()) {
                header('Location: /');
            }

            // Convertir datos a enteros solo si están presentes
            $_POST['jornada'] = isset($_POST['jornada']) ? (int)$_POST['jornada'] : null;
            $_POST['designado'] = isset($_POST['designado']) ? (int)$_POST['designado'] : null;
            $_POST['modalidad'] = isset($_POST['modalidad']) ? (int)$_POST['modalidad'] : null;

            $partido = new Partidos($_POST);

            // Validar el ID antes de consultar
            if (!empty($partido->id_partido) && ctype_digit($partido->id_partido)) {
                $existe = $partido->encuentra_partido($partido->id_partido);
            } else {
                $existe = null;
                Partidos::setAlerta('error', 'El ID de partido no es válido');
            }

            $alertas = $partido->validar_partido();

            // Si no hay errores y el ID no existe, guardar
            if (empty($alertas) && $existe === null) {
                $resultado = $partido->guardar();
                if ($resultado) {
                    sleep(2);
                    header('Location: /admin/partidos');
                }
            } else {
                Partidos::setAlerta('error', 'El ID de partido ya existe en la base de datos');
            }
        }

        $alertas = Partidos::getAlertas();

        // Render a la vista 
        $router->render('admin/partidos/agregar', [
            'titulo' => 'Agregar partido ',
            'alertas' => $alertas,
            'partido' => $partido,
            'usuario' => $usuario
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

        // Obtener el partido a editar:
        $partido = Partidos::find($id);
        //  debuguear($arbitro);

        // debuguear($modalidad);

        if (!$partido) {
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

            if (!$id) {
                desconectar();
            }

            // Obtener el partido a editar:
            $partido = Partidos::find($id);
            // debuguear($partido);

            //traer el árbitro designado al partido para datos para email
            $arbitro = Arbitros::find($partido->id_arbitro);
            // debuguear($arbitro);

            //Sincronizar con el post:
            $partido->sincronizar($_POST);

            // debuguear($partido);

            // Validar los datos
            $alertas = $partido->validar_partido();
            // debuguear($alertas);

            // Si no hay errores, guardar y enviar email con el cambio si está enviado o aceptado (estado 2 o 3)
            if (empty($alertas)) {

                if ($partido->estado === '2' || $partido->estado === '3') { //Estado del partido enviado o aceptado

                    // cambiamos estado del partido a enviado (estado 2) y añadimos observaciones
                    $partido->estado = 2;
                    $partido->observaciones = $_POST['observaciones'];

                    //Traemos la designacion para cambiar estado:
                    $designacion = Designaciones::encuentra_partido($partido->id_partido);
                    // debuguear($designacion);

                    //Cambiamos estado designacion a enviado (estado 2) Y añadimos observaciones
                    $designacion->estado = $partido->estado;
                    $designacion->observaciones = $partido->observaciones;

                    // debuguear($designacion);

                    $resultado = $partido->guardar();
                    $resultado2 = $designacion->guardar();

                    // debuguear($partido);
                    if ($resultado && $resultado2) {

                        //enviar email al árbitro con los cambios
                        $email = new Email([
                            'id' => $partido->id,
                            'id_partido' => $partido->id_partido,
                            'email' => $arbitro->email,
                            'apellido1' => $arbitro->apellido1,
                            'apellido2' => $arbitro->apellido2,
                            'nombre' => $arbitro->nombre,
                            'fecha' => $partido->fecha,
                            'hora' => $partido->hora,
                            'terreno' => $partido->terreno,
                            'categoria' => $partido->categoria,
                            'grupo' => $partido->grupo,
                            'local' => $partido->local,
                            'visitante' => $partido->visitante,
                            'observaciones' => $partido->observaciones
                        ]);

                        $resultado3 = $email->enviar_partido_editado();

                        if ($resultado3) {

                            sleep(2);
                            // Redireccionar a una página de éxito o lista
                            header('Location: /admin/partidos');
                        }
                    }
                } else { // Partido NO está enviado

                    $resultado = $partido->guardar();
                    if ($resultado) {
                        sleep(2);
                        // Redireccionar a una página de éxito o lista
                        header('Location: /admin/partidos');
                    }
                }
            }
        }

        $alertas = Partidos::getAlertas();

        // Render a la vista 
        $router->render('admin/partidos/editar', [
            'titulo' => 'Editar partido ',
            'alertas' => $alertas,
            'partido' => $partido,
            'usuario' => $usuario
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
            $partido = Partidos::find($id);

            if (!$partido) {
                echo json_encode(['success' => false, 'message' => 'Elemento no encontrado']);
                exit;
            }

            if ($partido->estado === '2' || $partido->estado === '3') {
                // Si el partido está en estado 'enviado' o 'aceptado', continuamos con el proceso
                $arbitro = Arbitros::find($partido->id_arbitro);

                if (!$arbitro) {
                    echo json_encode(['success' => false, 'message' => 'Elemento no encontrado']);
                    exit;
                }

                $designacion = Designaciones::encuentra_partido($partido->id_partido);

                if (!$designacion) {
                    echo json_encode(['success' => false, 'message' => 'Elemento no encontrado']);
                    exit;
                }

                // Eliminar el partido y la designación
                $resultado = $partido->eliminar();
                $resultado2 = $designacion->eliminar();

                if ($resultado && $resultado2) {
                    // Crear el objeto Email para enviar la cancelación
                    $email = new Email([
                        'id' => $partido->id,
                        'id_partido' => $partido->id_partido,
                        'email' => $arbitro->email,
                        'apellido1' => $arbitro->apellido1,
                        'apellido2' => $arbitro->apellido2,
                        'nombre' => $arbitro->nombre,
                        'fecha' => $partido->fecha,
                        'hora' => $partido->hora,
                        'terreno' => $partido->terreno,
                        'categoria' => $partido->categoria,
                        'grupo' => $partido->grupo,
                        'local' => $partido->local,
                        'visitante' => $partido->visitante,
                        'observaciones' => $partido->observaciones
                    ]);

                    // Enviar el correo de cancelación
                    $resultado3 = $email->cancelar_partido();

                    // Depuración: Log del resultado de cancelar_partido
                    error_log('Resultado de cancelar_partido: ' . var_export($resultado3, true));

                    // Comprobar si el correo se envió correctamente
                    if ($resultado3) {
                        echo json_encode(['success' => true]);
                        exit; // Detener la ejecución después de enviar la respuesta
                    } else {
                        echo json_encode(['success' => false, 'message' => 'No se pudo enviar el correo']);
                        exit; // Detener la ejecución
                    }
                }
            } else { // Partido ni enviado ni aceptado
                // Si el partido no está en estado 'enviado' ni 'aceptado', solo eliminamos el partido
                $resultado = $partido->eliminar();

                if ($resultado) {
                    echo json_encode(['success' => true]);
                    exit; // Detener la ejecución
                } else {
                    echo json_encode(['success' => false, 'message' => 'No se pudo eliminar']);
                    exit; // Detener la ejecución
                }
            }
        }
    }

    public static function autocompletarArbitrosAction()
    {
        header('Content-Type: application/json; charset=utf-8');

        $busqueda = $_POST['q'] ?? '';

        if (empty($busqueda)) {
            echo json_encode([]);
            return;
        }

        $busqueda = htmlentities($busqueda, ENT_QUOTES, 'UTF-8');
        $arbitros = Arbitros::busquedaParcial($busqueda);

        echo json_encode($arbitros ?: [], JSON_UNESCAPED_UNICODE);
    }

    public static function nombrar(Router $router)
    {

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Content-Type: application/json'); // Asegura que se devuelva JSON

            ob_clean(); // Limpia cualquier salida previa
            ob_end_clean(); // Asegura que el búfer esté completamente limpio


            $data = json_decode(file_get_contents('php://input'), true);
            // debuguear($data);

            $id_arbitro = $data['arbitro'];
            $id_partido = $data['partido'];

            error_log("ID recibido: $id_arbitro, Activo: $id_partido");

            //Traemos al árbitro 
            $arbitro = Arbitros::find($id_arbitro);
            // debuguear($arbitro);

            //traemos el partido
            $partido = Partidos::find($id_partido);
            // debuguear($partido);

            //Traemos las categorias
            $categorias = Categorias::all();

            //Se crea desgignacion
            $designacion = new Designaciones();

            //Clonamos el partido en el modelo desginacion:
            $designacion->id_usuario = $usuario->id;
            $designacion->id_arbitro = $arbitro->id;
            $designacion->id_partido = $partido->id_partido;
            $designacion->categoria = $partido->categoria;
            $designacion->grupo = $partido->grupo;
            $designacion->fecha = $partido->fecha;
            $designacion->hora = $partido->hora;
            $designacion->terreno = $partido->terreno;
            $designacion->distrito = $partido->distrito;
            $designacion->jornada = $partido->jornada;
            $designacion->local = $partido->local;
            $designacion->visitante = $partido->visitante;
            $designacion->observaciones = $partido->observaciones;
            $designacion->modalidad = (int)$partido->modalidad;

            foreach($categorias as $c) {
                if($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                    $designacion->facturar = $c->facturar;
                    $designacion->tarifa = $c->tarifa;
                    $designacion->pago_arbitro = $c->pago_arbitro;
                    $designacion->oa = $c->oa;
                }
            }
            //cambiamos estado a nombrado:
            $designacion->estado = 1;

            // debuguear($designacion);

            //Añadir al objeto partido los datos que faltan
            $partido->id_usuario = $usuario->id;
            $partido->id_arbitro = $arbitro->id;
            $partido->estado = 1;

            $resultado = $designacion->guardar();
            $resultado2 = $partido->guardar();

            if ($resultado && $resultado2) {
                echo json_encode([
                    'success' => true,
                    'message' => '¡Árbitro guardado correctamente!',
                    'partidoId' => $id_partido
                ]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Hubo un error al guardar los datos.']);
                exit;
            }
        }
    }

    public static function borrar_nombramiento(Router $router)
    {
        session_start();
        $usuario = new Perfiles($_SESSION);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Configurar encabezados para respuesta JSON
            header('Content-Type: application/json');
            http_response_code(200);

            try {
                // Decodificar JSON del cliente
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['arbitro']) || !isset($data['partido'])) {
                    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
                    exit;
                }

                $id_arbitro = $data['arbitro'];
                $id_partido = $data['partido'];

                // Obtener partido
                $partido = Partidos::find($id_partido);
                if (!$partido) {
                    echo json_encode(['success' => false, 'message' => 'Partido no encontrado.']);
                    exit;
                }

                // Obtener designación
                $designacion = Designaciones::encuentra_partido($partido->id_partido);
                if ($designacion && !$designacion->eliminar()) {
                    echo json_encode(['success' => false, 'message' => 'Error al eliminar la designación.']);
                    exit;
                }

                // Verificar estado del partido
                $enviarCorreo = false;
                if ($partido->estado == 2 || $partido->estado == 3) {
                    $enviarCorreo = true;
                }

                // Resetear datos del partido
                $partido->id_usuario = 0;
                $partido->id_arbitro = 0;
                $partido->estado = 0;
                $partido->observaciones = '';

                // Guardar cambios en el partido
                if (!$partido->guardar()) {
                    echo json_encode(['success' => false, 'message' => 'Error al guardar cambios en el partido.']);
                    exit;
                }

                // Si hay que enviar correo, proceder después de los cambios en la base de datos
                if ($enviarCorreo) {
                    $arbitro = Arbitros::find($id_arbitro);
                    if ($arbitro) {

                        $email = new Email([
                            'id' => $partido->id,
                            'id_partido' => $partido->id_partido,
                            'email' => $arbitro->email,
                            'apellido1' => $arbitro->apellido1,
                            'apellido2' => $arbitro->apellido2,
                            'nombre' => $arbitro->nombre,
                            'fecha' => $partido->fecha,
                            'hora' => $partido->hora,
                            'terreno' => $partido->terreno,
                            'categoria' => $partido->categoria,
                            'grupo' => $partido->grupo,
                            'local' => $partido->local,
                            'visitante' => $partido->visitante,
                            'observaciones' => $partido->observaciones
                        ]);


                        $resultadoCorreo = $email->cancelar_partido();

                        if (!$resultadoCorreo) {
                            // Registrar error, pero no detener el flujo si falla el correo
                            error_log('Error al enviar el correo al árbitro.');
                        }
                    }
                }

                // Responder éxito al cliente
                echo json_encode([
                    'success' => true,
                    'message' => '¡Árbitro borrado correctamente!',
                    'partidoId' => $id_partido
                ]);
                exit;
            } catch (Exception $e) {
                // Manejo de excepciones
                error_log('Error en el controlador: ' . $e->getMessage());
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']);
                exit;
            }
        }
    }

    public static function enviar_partido(Router $router)
    {

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            header('Content-Type: application/json'); // Asegura que se devuelva JSON

            ob_clean(); // Limpia cualquier salida previa
            ob_end_clean(); // Asegura que el búfer esté completamente limpio


            $data = json_decode(file_get_contents('php://input'), true);
            // debuguear($data);

            $id_arbitro = $data['arbitro'];
            $id_partido = $data['partido'];

            error_log("ID recibido: $id_arbitro, Activo: $id_partido");

            //Traemos al árbitro 
            $arbitro = Arbitros::find($id_arbitro);
            // debuguear($arbitro);

            //traemos el partido
            $partido = Partidos::find($id_partido);
            // debuguear($partido);

            $partido_id = $partido->id_partido;

            // Traemos la designación
            $designacion = Designaciones::encuentra_partido($partido_id);

            //Resetear el partido en la base de datos
            $partido->estado = 2; // Pasamos a estado 1 (enviado)

            //Resetear la designacion en la base de datos
            $designacion->estado = 2; // Pasamos a estado 1 (enviado)

            //Añadimos campos facturar, tarifa, pago_arbitro y oa a designacion
            $categorias = Categorias::all();
            foreach($categorias as $c) {
                if($designacion->categoria == $c->nombre2 && $designacion->modalidad == $c->id_modalidad) {
                    $designacion->tarifa = $c->tarifa;
                    $designacion->facturar = $c->facturar;
                    $designacion->pago_arbitro = $c->pago_arbitro;
                    $designacion->oa = $c->oa;
                }

                //Lo mismo pero para delegados de campo
                if($designacion->categoria == $c->nombre && $designacion->modalidad == $c->id_modalidad) {
                    $designacion->tarifa = $c->tarifa;
                    $designacion->facturar = $c->facturar;
                    $designacion->pago_arbitro = $c->pago_arbitro;
                    $designacion->oa = $c->oa;
                }
            }

            $resultado = $designacion->guardar();
            $resultado2 = $partido->guardar();

            //Instaciamos modelo email con los datos de la designación:
            $email = new Email([
                'id' => $designacion->id,
                'id_partido' => $designacion->id_partido,
                'email' => $arbitro->email,
                'apellido1' => $arbitro->apellido1,
                'apellido2' => $arbitro->apellido2,
                'nombre' => $arbitro->nombre,
                'fecha' => $designacion->fecha,
                'hora' => $designacion->hora,
                'terreno' => $designacion->terreno,
                'categoria' => $designacion->categoria,
                'grupo' => $designacion->grupo,
                'local' => $designacion->local,
                'visitante' => $designacion->visitante,
                'observaciones' => $designacion->observaciones
            ]);

            if ($resultado && $resultado2) {
                //Enviar email:
                $email->enviar_partido();

                echo json_encode([
                    'success' => true,
                    'message' => '¡Partido enviado con éxito!',
                    'partidoId' => $id_partido,
                    'arbitroId' => $id_arbitro
                ]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Hubo un error al guardar los datos.']);
                exit;
            }
        }
    }

    public static function autoaceptar(Router $router)
    {
        session_start();
        $usuario = new Perfiles($_SESSION);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Configurar encabezados para respuesta JSON
            header('Content-Type: application/json');
            http_response_code(200);

            try {
                // Decodificar JSON del cliente
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['arbitro']) || !isset($data['partido'])) {
                    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
                    exit;
                }

                $id_arbitro = $data['arbitro'];
                $id_partido = $data['partido'];

                // Obtener partido
                $partido = Partidos::find($id_partido);
                if (!$partido) {
                    echo json_encode(['success' => false, 'message' => 'Partido no encontrado.']);
                    exit;
                }

                // Obtener designación
                $designacion = Designaciones::encuentra_partido($partido->id_partido);
                if (!$designacion) {
                    echo json_encode(['success' => false, 'message' => 'Designación no encontrada.']);
                    exit;
                }

                // Resetear datos del partido
                $partido->estado = 3;

                // Resetear datos de la designacion
                $designacion->estado = 3;

                $resultado = $partido->guardar();
                $resultado2 = $designacion->guardar();

                if ($resultado && $resultado2) {
                    // Responder éxito al cliente
                    echo json_encode([
                        'success' => true,
                        'message' => '¡Partido aceptado con éxito!',
                        'partidoId' => $id_partido
                    ]);
                    exit;
                }
            } catch (Exception $e) {
                // Manejo de excepciones
                error_log('Error en el controlador: ' . $e->getMessage());
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error interno del servidor.']);
                exit;
            }
        }
    }

    public static function ver_partidos(Router $router)
    {

        session_start();
        // debuguear($_SESSION);

        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        //traemos todos los partidos
        $partidos_filtro = Partidos::all('ASC');

        //Traemos todos los arbitros
        $arbis = Arbitros::all_apellidos_y_nombre();

        //Traemos las categorias:
        $cate = Categorias::all('ASC');

        //Traemos las modalidades:
        $mod = Modalidades::all('ASC');

        //Cruzamos datos de partidos y arbitros para añadir el nombre y apellidos de los árbitros a los partidos
        foreach ($partidos_filtro as $p) {
            $p->nombre_arbitro = obtenerNombreArbitro($p, $arbis);
        }

        //Cruzamos datos de partidos y categorias 
        foreach ($partidos_filtro as $p) {
            $p->nombre_categoria = obtenerNombreCategoria($p, $cate);
        }

        //Cruzamos datos de partidos y categorias 
        foreach ($partidos_filtro as $p) {
            $p->nombre_modalidad = obtenerNombreModalidad($p, $mod);
        }


        echo json_encode($partidos_filtro);
    }
}
