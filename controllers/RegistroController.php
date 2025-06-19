<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Regalo;
use Model\Paquete;
use Model\Ponente;
use Model\Usuario;
use Model\Registro;
use Model\Categoria;
use Model\EventosRegistros;


class RegistroController {

    public static function crear(Router $router) {

        //valida si el usuario NO está autenticado o logueado
        if(!is_auth()) {
            //redirige al usuario al inicio
            header('Location: /');
        }

        //**Verificar si el id del usuario ya esta registrado en algún evento.
        //busca el id del usuario logueado, en la columna usario_id de la tabla registros 
        $registro = Registro::where('usuario_id', $_SESSION['id']);

        //Si el registro existe y el id del paquete registrado es "3" (gratis):
        if(isset($registro) && $registro->paquete_id === "3") {
            //redirige al usuario a la URL boleto con su id = token, que muestra su ticket
            header('Location: /boleto?id=' . urlencode($registro->token));
        }

        if(isset($registro) && $registro->paquete_id === "1") {
            header('Location: /finalizar-registro/conferencias');
        }


        //llamar render enviando el archivo para la vista y datos
        $router->render('registro/crear', [
            'titulo' => 'Finalizar Registro al Campus',
        ]);
    }

    //método para la inscripción de usuarios registrados, al plan gratuito
    public static function gratis() {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //verifica si el usuario NO está registrado o logueado
            if(!is_auth()) {
                //redirige al usuario
                header('Location: /login');
            }

            //** Verificar si el id del usuario ya esta registrado en algún evento.
            //busca el id del usuario logueado, en la columna usario_id de la tabla registros 
            $registro = Registro::where('usuario_id', $_SESSION['id']);

            //Si el registro existe y el id del paquete registraso es "3" (gratis):
            if(isset($registro) && $registro->paquete_id === "3") {
                //redirige al usuario a la URL boleto con su id = token, que muestra su ticket
                header('Location: /boleto?id=' . urlencode($registro->token));
            }



            //genera token random unico con md5(...),
            //sustrae del caracter 0 al 8 y lo asigna a $token
            $token = substr( md5(uniqid( rand(), true )), 0, 8);

            //**Crear registro para almacenar en la BD
            //define arreglo asociativo según Active Model y asignando datos
            $datos = [
                //3 es el id del paquete gratis
                'paquete_id' => 3,
                //el paquete gratis no requiere pago
                'pago_id' => '',
                'token' => $token,
                //obtiene el id del usuario de la sesión abierta al loguearse
                'usuario_id' => $_SESSION['id']
            ];

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

    //método para guardar en la DB, el registro a alguno de los planes de pago
    public static function pagar(Router $router) {
        
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //verifica si el usuario NO está registrado o logueado
            if(!is_auth()) {
                //redirige al usuario
                header('Location: /login');
            }

            //Valiar si POST, del fetch() POST de Paypal, viene vacio
            if(empty($_POST)) {
                //retorna un json vacio
                echo json_encode([]);
                //para el código aquí.
                return;
            }

            //**Crear registro en la tabla registros de la BD

            //genera token random unico con md5(...),
            //sustrae del caracter 0 al 8 y lo asigna a $token
            $token = substr( md5(uniqid( rand(), true )), 0, 8);

            //define y asigna a $datos, el arreglo recibido en $_POST, del fetch de paypal
            $datos = $_POST;
            //agrega la llave 'token' y el valor de $token, al arreglo $datos
            $datos['token'] = $token;
            //agrega la llave 'usuario_id' y el valor del id del usuario logueado
            $datos['usuario_id'] = $_SESSION['id'];
     
            try {
                //instancia y sincroniza el objeto del modelo Registro, con los $datos
                $registro = new Registro($datos);
                //gurada los datos del objeto del modelo $registro (Active Model), en la BD
                $resultado = $registro->guardar();
                //retorna objeto json, a la consulta fetch() de Paypal
                echo json_encode($resultado);

           } catch (\Throwable $th) {
                echo json_encode([
                    'resultado' => 'error'
                ]);
           }
        };
    }

    //método para elegir conferencias para los usuarios con Paquete Presencial
    public static function conferencias(Router $router) {

        //comprobar si el usuario no está autenticado
        if(!is_auth()) {
            header('Location: /login');
        }

        //**validar que el usuario logueado tenga registrado un Paquete Presencial
        //obtiene el id del usuario logueado y lo asigna
        $usuario_id = $_SESSION['id'];
        //busaca el usuario en la columna usuario_id, en tabla registros
        $registro = Registro::where('usuario_id', $usuario_id);

                //busca el id del registro en la columna registro_id de la tabla eventos_registros
                $registroFinalizado = EventosRegistros::where('registro_id', $registro->id);

                //valida si registro existe y si paquete_id es 2 (Pase Virtual)
                if(isset($registro) && $registro->paquete_id === "2") {
                    //redirige a la url /boleto enviando el token en el ?=id, para mostrar el boleto
                    header('Location: /boleto?id=' . urlencode($registro->token));
                    //para el código aquí.
                    return;
                }

                //valida si existe registroFinalizado
                if(isset($registroFinalizado)) {
                    //redirige a la url /boleto enviando el token en el ?=id, para mostrar el boleto
                    header('Location: /boleto?id=' . urlencode($registro->token));
                    //para el código aquí.
                    return;
                }


        //valida si el id paquete registrado por el usuario NO es 1 (Presencial)
        if($registro->paquete_id !== "1") {
            //redireccióna a inicio
            header('Location: /');
            return;
        }

        // //si el registro contiene un regalo_id, el registro se ha realizado
        // if(isset($registro->regalo_id)) {
        //     //redireccionar a la url boleto, enviando el token en el id de la url
        //     header('Location: /boleto?id=' . urlencode($registro->token));
        // }
        
        //ordenar() obtiene TODOS los eventos ordenados, 
        //según columna y tipo de ordenación. Requiere columna y orden
        $eventos = Evento::ordenar('hora_id', 'ASC');

        //define var tipo arreglo, vacio
        $eventos_formateados = [];

        //itera los $eventos y por cada objeto $evento:
        foreach($eventos as $evento) {
            
            //asignar, a cada objeto $evento, nuevas propiedades, con los valores
            //buscados en las diferentes tablas enlazadas
            $evento->categoria = Categoria::find($evento->categoria_id);
            $evento->dia = Dia::find($evento->dia_id);
            $evento->hora = Hora::find($evento->hora_id);
            $evento->ponente = Ponente::find($evento->ponente_id);
            
            //**Agrupar los eventos por categoría y día */
            //si la propiedad categoria_id del obejeto $evento, es = 1 (Conferencia) y
            //la propiedad dia_id, es = 1 (Viernes)
            if($evento->categoria_id === "1" & $evento->dia_id === "1") {
                // agrega el $evento (Conferencia Viernes) en 
                // la llave ['conferencias_v'] del agrreglo $eventos_formateados
                $eventos_formateados['conferencias_v'][] = $evento;
            }

            //si la propiedad categoria_id del obejeto $evento, es = 1 (Conferencia) y
            //la propiedad dia_id, es = 2 (Sábado)
            if($evento->categoria_id === "1" & $evento->dia_id === "2") {
                // agrega el $evento (Conferencia Sabado) en 
                // la llave ['conferencias_s'] del agrreglo $eventos_formateados
                $eventos_formateados['conferencias_s'][] = $evento;
            }

            //si la propiedad categoria_id del obejeto $evento, es = 2 (Workshop) y
            //la propiedad dia_id, es = 1 (Viernes)
            if($evento->categoria_id === "2" & $evento->dia_id === "1") {
                // agrega el $evento (Workshop Viernes) en 
                // la llave ['workshops_v'] del agrreglo $eventos_formateados
                $eventos_formateados['workshops_v'][] = $evento;
            }

            //si la propiedad categoria_id del obejeto $evento, es = 2 (Workshop) y
            //la propiedad dia_id, es = 2 (Sábado)
            if($evento->categoria_id === "2" & $evento->dia_id === "2") {
                // agrega el $evento (Workshop Sábado) en 
                // la llave ['workshops_s'] del agrreglo $eventos_formateados
                $eventos_formateados['workshops_s'][] = $evento;
            }
        }

        //obtiene todos los regalos en orden ASC, para pasarlos al render
        $regalos = Regalo::all('ASC');

        // Manejo del registro de eventos mediante el $_POST
        //si el método de la consulta al server es tipo POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //comprobar si el usuario no está autenticado
            if(!is_auth()) {
                header('Location: /login');
            }

            //['eventos'] contiene una cadena de strigs separados por comas,
            //con los id de los eventos registrados. Requerimos separarlos y guardarlos
            //en un arreglo para poder acceder a los id de los eventos, por separado
            $eventos = explode(',', $_POST['eventos']);

            //valida si eventos está vacio
            if(empty($eventos)) {
                //crea array asoc con clave 'resultado' valor false,
                //convierte el array asoc en un objeto json y lo retorna como respuesta
                echo json_encode(['resultado' => false]);
                //para el código aquí
                return;
            }

            // Obtener el registro de usuario de la tabla, enviando columna y id
            $registro = Registro::where('usuario_id', $_SESSION['id']);

            //valida si $registro NO existe y NO es diferente de null ó ||
            //el valor de paquete_id en $registro, NO es igual a 1 (Presencial)
            if(!isset($registro) || $registro->paquete_id !== "1") {
                //crea array asoc con clave 'resultado' valor false,
                //convierte el array asoc en un objeto json y lo retorna como respuesta
                echo json_encode(['resultado' => false]);
                //para el código aquí
                return;
            }
            
            //define arreglo para almacenar los eventos existentes y con disponibilidad
            $eventos_array = [];

            //** Validar la disponibilidad de cada uno de los eventos seleccionados por el user.
            //Iterea el arreglo $eventos con los id de los eventos seleccionados y por cada evento_id
            foreach($eventos as $evento_id) {
                //busca el evento por su id, en la tabla eventos, de la DB
                $evento = Evento::find($evento_id);

                //valida si el evento NO existe o || la cantidad de eventos disponibles es = "0"
                if(!isset($evento) || $evento->disponibles === "0") {
                    //crea array asoc con clave 'resultado' valor false,
                    //convierte el array asoc en un objeto json y lo retorna como respuesta
                    echo json_encode(['resultado' => false]);
                    //para el código aquí
                    return;
                }

                //agrega cada evento validado (objeto), al final del arreglo
                $eventos_array[] = $evento;
            }

            //Iterea el arreglo de objetos $eventos_array eventos seleccionados y validados
            // y por cada objeto $evento:
            foreach($eventos_array as $evento) {
                //a la cantida de eventos disponibles de cada evento, le resta uno
                $evento->disponibles -= 1;
                //guardar todo el evento en la BD con la nueva cantidad de disponibles
                $evento->guardar();

                //**Almacenar los eventos y el registro en la tabla pivote eventos_registros
                //define arreglo asoc según el modelo Active Record y le asigna llaves y valores
                //convertidos a tipo int 
                $datos = [
                    'evento_id' => (int) $evento->id,
                    'registro_id' => (int) $registro->id
                ];

                //instalcia el modelo EventosRegistros y le asigna los valoresen $datos;
                $registro_usuario = new EventosRegistros($datos);
                //almacena los eventos y el registro en la tabla eventos_registros de la DB
                $registro_usuario->guardar();
            }

            //** Almacenar el regalo_id en la tabla registros de la DB
            //sincroniza solo la llave regalo_id de arreglo $egistro,
            //con el valor de la llave regalo_id del arreglo en POST
            $registro->sincronizar(['regalo_id' => $_POST['regalo_id']]);

            //guardar el objeto registro en la DB y retorna un resultado bool
            $resultado = $registro->guardar();

            //si el $resultado de guardar() es true
            if($resultado) {
                //crea array asoc con llave 'resultado' y el valor de $resultado (true),
                // la llave token y el valor de token en $registro,
                //convierte el array asoc en un objeto json y lo retorna como respuesta
                echo json_encode([
                    'resultado' => $resultado,
                    'token' => $registro->token
                ]);

            } else {
                //crea array asoc con clave 'resultado' valor false,
                //convierte el array asoc en un objeto json y lo retorna como respuesta
                echo json_encode(['resultado' => false]);
            }

            //para el código aquí
            return;

        }

        //llamar render enviando el archivo para la vista y datos
        $router->render('registro/conferencias', [
            'titulo' => 'Elige Talleres y Conferencias',
            'eventos' => $eventos_formateados,
            'regalos' => $regalos
        ]);
    }

}