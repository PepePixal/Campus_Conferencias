<?php

namespace Controllers;

use MVC\Router;

class RegalosController {

    public static function index(Router $router) {

        //comprueba si el usuario no es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
            return;
        }

        $router->render('admin/regalos/index', [
            'titulo' => 'Regalos'
        ]);
    }
}