<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Arbitros;
use Model\Partidos;
use Model\Perfiles;
use Model\Categorias;
use Model\Modalidades;
use Model\Designaciones;
use PhpOffice\PhpSpreadsheet\Calculation\Category;

class FacturasController
{

    public static function index(Router $router)
    {
        if (!is_admin()) {
            header('Location: /');
        }

        session_start();

        $usuario = new Perfiles($_SESSION);

        // Obtener todas las designaciones
        $jornada = Designaciones::all();
        $jornada_generada = true;

        foreach ($jornada as $j) {
            if ($j->jornada_editada == 0) {
                $jornada_generada = false;
                break;
            }
        }

        if (empty($jornada)) {
            $jornada = [];
            $jornada_generada = false;
        }

        // ✅ Agrupar designaciones por jornada_editada
        $jornadas_agrupadas = [];
        foreach ($jornada as $j) {
            $jornada_editada = $j->jornada_editada;
            if (!isset($jornadas_agrupadas[$jornada_editada])) {
                $jornadas_agrupadas[$jornada_editada] = [];
            }
            $jornadas_agrupadas[$jornada_editada][] = $j;
        }

        // Render a la vista 
        $router->render('admin/facturas/index', [
            'titulo' => 'Gestión de Jornada',
            'usuario' => $usuario,
            'jornada' => $jornada,
            'jornada_generada' => $jornada_generada,
            'jornadas_agrupadas' => $jornadas_agrupadas
        ]);
    }

    public static function generar_jornada(Router $router)
    {
        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
    
        $alertas = [];
        $usuario = new Perfiles($_SESSION);
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validamos el valor de jornada
            $numero_jornada = $_POST['jornada'] ?? null;
    
            if (!$numero_jornada || !is_numeric($numero_jornada)) {
                $alertas[] = 'El número de jornada es obligatorio y debe ser un valor numérico.';
            } else {
                // Traemos todas las designaciones
                $jornada = Designaciones::all();
    
                foreach ($jornada as $j) {
                    // ✅ Solo modificar si jornada_editada es 0
                    if ($j->jornada_editada == 0) {
                        $j->jornada_editada = $numero_jornada;
    
                        // Validamos formulario
                        $alertas = $j->validar_generar_designacion();
    
                        if (empty($alertas)) {
                            $resultado = $j->guardar();
                        }
                    }
                }
    
                if (empty($alertas)) {
                    header('Location: /admin/facturas');
                    exit;
                }
            }
        }
    
        // Render a la vista 
        $router->render('admin/facturas/generar_jornada', [
            'titulo' => 'Generar Jornada',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    
    public static function ver (Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        //Sacamos el titulo (que es el número de jornada editada) del get
        $titulo = $_GET['jornada_editada'];
        // debuguear($titulo);

        // Traemos designaciones cuya jornada_editada sea esa
        $designaciones = Designaciones::where_all('jornada_editada', $titulo);
        // debuguear($designaciones);

        //Traemos las modalidades
        $modalidades = Modalidades::all();

        // Traemos los usuarios
        $arbitros = Arbitros::all();

        //Traemos las categorias
        $categorias = Categorias::all();
        // debuguear($categorias);

        // Render a la vista 
        $router->render('admin/facturas/ver', [
            'titulo' => 'Jornada ' . $titulo,
            'usuario' => $usuario,
            'designaciones' => $designaciones,
            'arbitros' => $arbitros,
            'categorias' => $categorias,
            'modalidades' => $modalidades
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

        //Recupero el id de designación del get
        $id_designacion = $_GET['id'];
        //Traigo la designación con ese id
        $designacion_editar = Designaciones::find($id_designacion);
        $jornada_editada = $designacion_editar->jornada_editada;

        //Traemos todos los arbitros
        $arbitros = Arbitros::all();

        //Traemos todas las categorias
        $categorias = Categorias::all();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // debuguear($_POST);
            
            //Transformar el dato unidad del post en decimal
            $_POST['unidad'] = (float)$_POST['unidad'];

            //Traemos designacion a editar
            $designacion = Designaciones::find($id_designacion);

            //Sincronizamos con el post
            $designacion->sincronizar($_POST);

            //Añado datos del post al objeto designacion
            $designacion->id_arbitro = s($_POST['arbitro']);
            $designacion->categoria = s($_POST['categoria']);
            $designacion->grupo = s($_POST['grupo']);
            $designacion->fecha = s($_POST['fecha']);
            $designacion->hora = s($_POST['hora']);
            $designacion->terreno = s($_POST['terreno']);
            $designacion->distrito = s($_POST['distrito']);
            $designacion->local = s($_POST['local']);
            $designacion->visitante = s($_POST['visitante']);
            $designacion->observaciones = s($_POST['observaciones']);
            $designacion->modalidad = s($_POST['modalidad']);

            // debuguear($designacion);

            // if($designacion->modalidad == 1) { //Si es futbol
            //     if($designacion->unidad == 1.00) {

            //     } else if($designacion->unidad == 0.50) {

            //     } else if($designacion->unidad == 0.25) {

            //     }

            // } else if ($designacion->modalidad ==2) { //Si es Sala
            //     if($designacion->unidad == 1.00) {

            //     } else if($designacion->unidad == 0.50) {

            //     } else if($designacion->unidad == 0.25) {
                    
            //     }

            // }

            $alertas = $designacion->validar_designacion();

            // debuguear($alertas);

            if(empty($alertas)) {
                $resultado = $designacion->guardar();
                // debuguear($resultado);

                if($resultado) {
                    sleep(2);
                    header('Location: /admin/facturas/ver?jornada_editada='. $designacion->jornada_editada);
                }
            }
        }

        // Render a la vista 
        $router->render('admin/facturas/editar', [
            'titulo' => 'Editar designación',
            'usuario' => $usuario,
            'designacion_editar' => $designacion_editar,
            'jornada_editada' => $jornada_editada,
            'arbitros' => $arbitros,
            'categorias' => $categorias,
            'alertas' => $alertas
        ]);
    }
}
