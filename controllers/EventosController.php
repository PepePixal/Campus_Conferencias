<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Categoria;

class EventosController {

    public static function index(Router $router) {

        $router->render('admin/eventos/index', [
            'titulo' => 'Conferencias y Workshops'
        ]);
    }

    public static function crear(Router $router) {

        $alertas = [];

        //obtiene las categorias de la DB, con el model Categoria
        //envía el orden ASCencente como parámetro. (Conf y Works)
        $categorias = Categoria::all('ASC');
        //obtiene los días de la DB, con el model Dia,
        //envía el orden ASCencente como parámetro. (Sábado Viernes)
        $dias = Dia::all('ASC');
        //obtiene las horas de la DB, con el model Hora,
        //envía el orden ASCencente como parámetro. (Sábado Viernes)
        $horas = Hora::all('ASC');

        //instancia de nuevo objeto modelo Evento.
        $evento = new Evento;

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //sincroniza el objeto $evento con el contenido de $_POST
            $evento->sincronizar($_POST);

            //valida las propiedades del objeto $evento sincronizado.
            //Retorna arreglo con alertas de validación, si las hay
            $alertas = $evento->validar();

            //si $alertas está vacio, se ha pasado la validación
            if(empty($alertas)) {

                //guardar el evento en la tabla evento de la DB
                $resultado = $evento->guardar();

                //si hay $resultado, se ha guardado correctamente
                if($resultado) {
                    //redirigir al endpoint eventos
                    header('Location: /admin/eventos');
                }
            }
        }


        $router->render('admin/eventos/crear', [
            'titulo' => 'Conferencias y Workshops',
            'alertas' => $alertas,
            'categorias' => $categorias,
            'dias' => $dias,
            'horas' => $horas,
            'evento' => $evento
        ]);
    }
}

