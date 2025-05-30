<?php

namespace Controllers;

use Model\EventosHorario;

class APIEventos {

    public static function index() {

        //asigna a $dia_id,  el valor del elemento con clave dia_id,
        //del arreglo de la var superglobal $_GET
        $dia_id = $_GET['dia_id'] ?? '';
        //asigna a $categoria_id, el valor del elemento con clave categoria_id,
        //del arreglo de la var superglobal $_GET
        $categoria_id = $_GET['categoria_id'] ?? '';
        
        //filtra el valor entero de la variable y lo reasigna
        $dia_id = filter_var($dia_id, FILTER_VALIDATE_INT);
        $categoria_id = filter_var($categoria_id, FILTER_VALIDATE_INT);

        //valida, si no hay valor en alguna de las dos variables
        if(!$dia_id || !$categoria_id) {
            //pinta un objeto tipo json vacio y retorne para no seguir ejecutando
            echo json_encode([]);
            return;
        }

        //consulta a la DB los eventos que contengan la valores enviados como argumento,
        //el nombre de las llaves para las columnas y los valores a buscar.
        // El ?? [], es para que retorne [] si es null
        $eventos = EventosHorario::whereArray(['dia_id' => $dia_id, 'categoria_id' => $categoria_id]) ?? [];

        echo json_encode($eventos);
    }
}