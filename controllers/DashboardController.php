<?php

namespace Controllers;

use MVC\Router;
use Model\Evento;
use Model\Usuario;
use Model\Registro;

class DashboardController {

    public static function index(Router $router) {

        //** Obtener */
        //obtiene los últimos 5 registros a eventos, de la tabla registros
        $registros = Registro::get(5);

        //para obtener información de otras tablas relacionadas con registros,
        //itera los registros obtenidos y por cada registro:
        foreach ($registros as $registro) {
            //agrega la clave usuario a cada registro y 
            //le asigna el objeto usuario obtenido de la búsqueda
            $registro->usuario = Usuario::find($registro->usuario_id);
        }

        //** Calcular los ingresos */
        //obtiene el total de registros con paquete virtual
        $virtuales = Registro::total('paquete_id', 2);
        //obtiene el total registros con paquete presencial
        $presenciales = Registro::total('paquete_id', 1);

        //calcula los ingresos según el precio del tipo de paquete, menos la comisión PayPal
        $ingresos = ($virtuales * 46.41) + ($presenciales * 189.54);

        //** Obtener eventos con menor y mayor plazas disponibles */
        // Obtener eventos con menor cantidad de plazas disponibles
        $menos_disponibles = Evento::ordenarLimite('disponibles','ASC', 5);
        // Obtener eventos con mayor cantidad de plazas disponibles
        $mas_disponibles = Evento::ordenarLimite('disponibles','DESC', 5);


        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de Administración',
            'registros' => $registros,
            'ingresos' => $ingresos,
            'menos_disponibles' => $menos_disponibles,
            'mas_disponibles' => $mas_disponibles
        ]);
    }
}