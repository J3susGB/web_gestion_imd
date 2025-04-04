<?php

namespace Controllers;

use MVC\Router;
use Model\Perfiles;

class InformesController {

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
        $router->render('admin/informes/index', [
            'titulo' => 'Informes',
            'usuario' => $usuario,
        ]);
    }

    public static function partidos(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        // Render a la vista 
        $router->render('admin/informes/informes_partidos', [
            'titulo' => 'Informes de partidos',
            'usuario' => $usuario,
        ]);
    }

    public static function distritos(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        // Render a la vista 
        $router->render('admin/informes/informes_distritos', [
            'titulo' => 'Informes de Distritos',
            'usuario' => $usuario,
        ]);
    }

    public static function arbitros(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        // Render a la vista 
        $router->render('admin/informes/informes_arbitros', [
            'titulo' => 'Informes de árbitros/as',
            'usuario' => $usuario,
        ]);
    }

    public static function economicos(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        // Render a la vista 
        $router->render('admin/informes/informes_economicos', [
            'titulo' => 'Informes económicos',
            'usuario' => $usuario,
        ]);
    }
    public static function usuarios(Router $router)
    {

        if (!is_admin()) {
            header('Location: /');
        }
        session_start();
        // debuguear($_SESSION);
        $usuario = new Perfiles($_SESSION);
        // debuguear($usuario);

        // Render a la vista 
        $router->render('admin/informes/informes_usuarios', [
            'titulo' => 'Informes de usuarios',
            'usuario' => $usuario,
        ]);
    }

}