<?php

namespace Controllers;

use Model\Regalo;
use Model\Registro;

class APIRegalos {

    public static function index() {

        //obtiene todos los tipos de regalos
        $regalos = Regalo::all();

        //itera el arreglo regalos y por cada objeto regalo:
        foreach($regalos as $regalo) {
            //agrega la propiedad total a cada objeto $regalo y le asigna
            //la cantidad total de registros obtenidos con el método totalArray()
            //que cumplan las condiciones, enviadas al método en un arreglo, como parámetro.
            $regalo->total = Registro::totalArray(['regalo_id' => $regalo->id, 'paquete_id' => "1"]);
        }

        //retorna imprimiendo en la url api/regalos,
        //con todos los objetos regalos en formato json
        echo json_encode($regalos);
        //para el código aquí.
        return;

       
    }
}