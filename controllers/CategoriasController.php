<?php

namespace Controllers;

use Model\Categorias;
use Model\Designaciones;
use Model\Modalidades;
use Model\Partidos;
use Model\Perfiles;
use MVC\Router;

class CategoriasController {

    public static function index (Router $router)
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

    public static function agregar (Router $router)
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

            if(empty($alertas)) {
                //Guardamos en base de datos
                $resultado = $categoria->guardar();

                if($resultado) {
                    sleep(2);
                    header('Location: /admin/categorias');
                }
            }
        }

        // Render a la vista 
        $router->render('admin/categorias/agregar', [
            'titulo' => 'Agregar Categoría',
            'alertas' =>$alertas,
            'categoria' => $categoria
        ]);
    }

}
