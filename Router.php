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

        $url_actual = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$url_actual] ?? null;
        } else {
            $fn = $this->postRoutes[$url_actual] ?? null;
        }

        if ( $fn ) {
            call_user_func($fn, $this);
        } else {
            //header('Location: /404');
        }
    }

    //recibe la vista y los datos
    public function render($view, $datos = []) {
        
        foreach ($datos as $key => $value) {
            $$key = $value; 
        }

        //inicia el almacenamiento en búfer de salida para
        //capturar la ejecución del archivo de la vista
        ob_start(); 

        //carga la vista .php de /views/, según el argumento $view, 
        include_once __DIR__ . "/views/$view.php";

        //captura el contenido de la vista y lo almacena en $contenido
        //y despues limpia el búfer.
        $contenido = ob_get_clean();


        //** Utilizar un Layout (vista principal) según la URL */
        //obtiene la URL actual, tipo string
        $url_actual = $_SERVER['PATH_INFO'] ?? '/';

        //si el string en $url_actual, contiene '/admin'
        if(str_contains($url_actual, '/admin')) {
            //carga el layout base administración, de la página para usuario admin,
            // donde se integrará la vista recibida que está almacenada en $contenido.
            include_once __DIR__ . '/views/admin-layout.php';
        
        //si el string en $url_actual, NO contiene '/admin'
        } else {
            //carga el layout base normal, de la página para usuario no admin,
            // donde se integrará la vista recibida que está almacenada en $contenido.
            include_once __DIR__ . '/views/layout.php';
        }

    }
}
