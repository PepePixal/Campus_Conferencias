<?php

namespace Controllers;

use Model\Ponente;

class APIPonentes {

    public static function index() {
        //obtiene todos los ponentes de la BD
        $ponentes = Ponente::all();
        echo json_encode($ponentes);
    }

    //método para obtener un ponente por su id
    public static function ponente() {
        //obtiene el id de la URL en $_GET
        $id = $_GET['id'];
        //filtra para obtener un entero
        $id = filter_var($id, FILTER_VALIDATE_INT);

        //valida si no existe $id o es menor a 1
        if(!$id || $id < 1) {
            //imprime arreglo vacio json
            echo json_encode([]);
            //retorna y para el código
            return;
        }

        //busca ponente por su id con el modelo Ponente
        $ponente = Ponente::find($id);
        //imprime el ponente codificado a json, escapando los slashes (\)
        echo json_encode($ponente, JSON_UNESCAPED_SLASHES);
    }
}