<?php

namespace Controllers;

use MVC\Router;

class PaginasController {
    
    public static function index(Router $router) {

        $router->render( 'paginas/index', [
            'titulo' => 'Inicio'
        ]);
    }
    
    public static function evento(Router $router) {

        $router->render( 'paginas/campusdevweb', [
            'titulo' => 'Sobre CampusDevWeb'
        ]);
    }
    
    public static function precios(Router $router) {

        $router->render( 'paginas/precios', [
            'titulo' => 'Precios CampusDevWeb'
        ]);
    }
    
    public static function conferencias (Router $router) {

        $router->render( 'paginas/conferencias', [
            'titulo' => 'Talleres y Conferencias'
        ]);
    }


}