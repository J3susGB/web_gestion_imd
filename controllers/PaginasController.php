<?php

namespace Controllers;

use Model\Designaciones;
use Model\Partidos;
use Model\Perfiles;
use MVC\Router;

class PaginasController
{

    public static function aceptar(Router $router)
    {

        $id_partido = $_GET['id_partido']; // Leer el id de la url
        // debuguear($id_partido);

        //Localizamos partido a traves del id partido en la base de datos
        $partido = Partidos::encuentra_partido($id_partido);
        // debuguear($partido);

        //Localizamos designacion a traves del id partido en la bd
        $designacion = Designaciones::encuentra_partido($id_partido);
        // debuguear($designacion);

        if ($partido->estado == 2 || $partido->estado == 4) {

            //Cambiamos estado al partido y a la designación
            $partido->estado = 3;
            $designacion->estado = 3;

            // Guardamos en la base de datos
            $resultado = $partido->guardar();
            $resultado2 = $designacion->guardar();
        }


        // Render a la vista 
        $router->render('paginas/aceptar', [
            'titulo' => 'Partido aceptado',
            'partido' => $partido
        ]);
    }

    public static function motivo_rechazo(Router $router)
    {

        $id_partido = $_GET['id_partido']; // Leer el id de la url
        // debuguear($id_partido);

        //Localizamos partido a traves del id partido en la base de datos
        $partido = Partidos::encuentra_partido($id_partido);
        // debuguear($partido);

        //Localizamos designacion a traves del id partido en la bd
        $designacion = Designaciones::encuentra_partido($id_partido);
        // debuguear($designacion);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // debuguear($_POST);

            $id_partido = $_GET['id_partido']; // Leer el id de la url
            // debuguear($id_partido);

            //Localizamos partido a traves del id partido en la base de datos
            $partido = Partidos::encuentra_partido($id_partido);
            // debuguear($partido);

            //Localizamos designacion a traves del id partido en la bd
            $designacion = Designaciones::encuentra_partido($id_partido);
            // debuguear($designacion);

            if ($partido->estado == 2) {

                //Cambiamos estado al partido y a la designación
                $partido->estado = 4;
                $designacion->estado = 4;

                //Asigno valor del post a las observaciones
                $partido->observaciones = $_POST['motivo'];
                $designacion->observaciones = $_POST['motivo'];
                
                // Guardamos en la base de datos
                $resultado = $partido->guardar();
                $resultado2 = $designacion->guardar();

                if($resultado && $resultado2) {
                    sleep(1);
                    header('Location: /paginas/rechazar?id_partido='.$partido->id_partido);
                }
            }
        }

        // Render a la vista 
        $router->render('paginas/motivo_rechazo', [
            'titulo' => 'Motivo de Rechazo',
            'partido' => $partido
        ]);
    }

    public static function rechazar(Router $router) {

        $id_partido = $_GET['id_partido']; // Leer el id de la url

        // Render a la vista 
        $router->render('paginas/rechazar', [
            'id_partido' => $id_partido
        ]);
    }
}
