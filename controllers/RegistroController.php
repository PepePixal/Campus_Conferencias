<?php

namespace Controllers;

use MVC\Router;
use Model\Paquete;
use Model\Usuario;
use Model\Registro;


class RegistroController {

    public static function crear(Router $router) {

        //llamar render enviando el archivo para la vista y datos
        $router->render('registro/crear', [
            'titulo' => 'Finalizar Registro al Campus',
        ]);
    }

    //método para la inscripción de usuarios registrados, al plan gratuito
    public static function gratis(Router $router) {
        
      if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //verifica si el usuario NO está registrado o logueado
        if(!is_auth()) {
            //redirige al usuario
            header('Location: /login');
        }

        //genera token random unico con md5(...),
        //sustrae del caracter 0 al 8 y lo asigna a $token
        $token = substr( md5(uniqid( rand(), true )), 0, 8);

        //**Crear registro para almacenar en la BD
        //define arreglo asociativo según Active Model y asignando datos
        $datos = array(
            //3 es el id del paquete gratis
            'paquete_id' => 3,
            //el paquete gratis no requiere pago
            'pago_id' => '',
            'token' => $token,
            //obtiene el id del usuario de la sesión abierta al loguearse
            'usuario_id' => $_SESSION['id']
        );

        //instancia y sincroniza el objeto del modelo Registro, con los $datos
        $registro = new Registro($datos);
        //gurada los datos del objeto del modelo $registro (Active Model), en la BD
        $resultado = $registro->guardar();
        //valida si hay resultado y no es false
        if($resultado) {
            //redirecciona al usuario, a la url /boleto con ?id= al token generado para el registro
            //la func php urlencode() toma el string y lo convierte en un formato seguro para URL
            header('Location: /boleto?id=' . urlencode($registro->token));
        } 
      };
    }

     public static function boleto(Router $router) {

        //** Validar la URL y su id
        //obtiene el id (token) de la URL, en $_GET
        $id = $_GET['id'];

        // valida si no hay $id o su longitud no es = a 8 carácteres
        if(!$id || !strlen($id) === 8) {
            //redirege al usuario al inicio
            header('Location: /');
        }

        //Buscar el $id de la URL, en la columna token de la tabla registros de la BD y
        //asigna el objeto modelo Registra a $registro
        $registro = Registro::where('token', $id);

        //valida si NO hay objeto registro o es nulo
        if(!$registro) {
            //redirige al usuario al inicio
            header('Location: /');
        }

        //**obtener la info de las tablas relacionadas con la tabla registro
        //busca el usuario en la tabla usuarios, según el usuario_id del objeto registro y
        //asigna el objeto retornado, como nueva propiedad usuario, al objeto $registro.
        //Para evitar el mensaje DEPRECATED, requiere agregar la propiedad usuario al model Registro.php
        $registro->usuario = Usuario::find($registro->usuario_id);
        //busca el paquete en la tabla paquetes, según el paquete_id del objeto registro y
        //asigna el objeto retornado, como nueva propiedad paquete, al objeto $registro.
        //Para evitar el mensaje DEPRECATED, requiere agregar la propiedad paquete al model Registro.php
        $registro->paquete = Paquete::find($registro->paquete_id);

        //llamar render enviando el archivo para la vista y datos
        $router->render('registro/boleto', [
            'titulo' => 'Inscripción Virtual',
            'registro' => $registro
        ]);
    }
}