<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;

class PaginasController {
    
    public static function index(Router $router) {

        //ordenar() obtiene TODOS los registros ordenados, 
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

            // Obtener el total de cada bloque
            $ponentes_total = Ponente::total();
            $conferencias_total = Evento::total_columna_valor('categoria_id', 1);
            $talleres_total = Evento::total_columna_valor('categoria_id', 2);

            // Obtener todos los ponentes
            $ponentes = Ponente::all();

        $router->render( 'paginas/index', [
            'titulo' => 'Inicio',
            'eventos' => $eventos_formateados,
            'ponentes_total' => $ponentes_total,
            'conferencias_total'=> $conferencias_total,
            'talleres_total' => $talleres_total,
            'ponentes' => $ponentes
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

        //ordenar() obtiene TODOS los registros ordenados, 
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

        $router->render( 'paginas/conferencias', [
            'titulo' => 'Talleres y Conferencias',
            'eventos' => $eventos_formateados
        ]);
    }

}