<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function comprobarRutas()
    {

        // $url_actual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/'; //Por defecto
        $url_actual = strtok(urldecode($_SERVER['REQUEST_URI']), '?') ?? '/';

        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$url_actual] ?? null;
        } else {
            $fn = $this->postRoutes[$url_actual] ?? null;
        }

        if ( $fn ) {
            call_user_func($fn, $this);
        } else {
            header('Location: /404');
        }
    }

    public function render($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value; 
        }

        ob_start(); 

        include_once __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Limpia el Buffer

        //Utilizar el layout de acuerdo a la URL (Lo modifico para que lea el admin layout):
        $url_actual = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';

        if(str_contains($url_actual, '/admin')) {
            include_once __DIR__ . '/views/admin_layout.php';
        } else if(str_contains($url_actual, '/usuario')) {
            include_once __DIR__ . '/views/usuario_layout.php';
        } else {
            include_once __DIR__ . '/views/layout.php';
        }

    }
}
