<?php

namespace Controllers;

use Model\Ponente;

class APIPonentes {

    public static function index() {
        //obtiene todos los ponentes de la BD
        $ponentes = Ponente::all();

        echo json_encode($ponentes);
    }
}