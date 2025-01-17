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

class DashboardController {

    public static function index(Router $router) {

        if(!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);
        
        // Render a la vista 
        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de control',
            'usuario'=> $usuario
        ]);
    }

    public static function index_usuario(Router $router) {

        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        //Proteger la vista
        if(!$usuario->id) {
            desconectar();
            header('Location: /');
        } else {
            $user_comprobado = Perfiles::find($usuario->id);
            if(!$user_comprobado) {
                desconectar();
                header('Location: /');
            }
        }

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

        // Capturar filtros si vienen por GET
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {

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

            // debuguear($_POST);

            $envio_partidos = $_POST['seleccionar'];
            // debuguear($envio_partidos);

            //Nos traemos todos los partidos
            $partidos = Partidos::all();
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

                                //Guardamos cambios en la BD
                                $resultado = $partido->guardar();
                                $resultado2 = $designacion->guardar();

                                if ($resultado && $resultado2) {
                                    //Enviamos designaciones por correo
                                    $email = new Email(
                                        $designacion->id,
                                        $designacion->id_partido,
                                        $a->email,
                                        $a->apellido1,
                                        $a->apellido2,
                                        $a->nombre,
                                        $designacion->fecha,
                                        $designacion->hora,
                                        $designacion->terreno,
                                        $designacion->categoria,
                                        $designacion->grupo,
                                        $designacion->local,
                                        $designacion->visitante,
                                        $designacion->observaciones
                                    );

                                    $email->enviar_partido();
                                    header('Location: /usuario/dashboard');
                                }
                            }
                        }
                    }
                }
            }
        }

        // Render a la vista 
        $router->render('usuario/dashboard/index', [
            'titulo' => 'Gestión de partidos',
            'partidos' => $partidos,
            'categorias' => $categorias,
            'fechasAsociativo' => $fechasAsociativo,
            'distritosAsociativo' => $distritosAsociativo,
            'usuario' => $usuario,
            'arbitros' => $arbitros,
            'designaciones' => $designaciones,
            'filtros' => $_GET ?? [],
            'sinResultados' => $sinResultados
        ]);
    }
}