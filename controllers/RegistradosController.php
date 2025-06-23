<?php

namespace Controllers;

use MVC\Router;
use Model\Paquete;
use Model\Usuario;
use Model\Registro;
use Classes\Paginacion;

class RegistradosController {

    public static function index(Router $router) {

        //comprueba si el usuario NO es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
        }
        
        //** Paginación
        //obtiene la página actual, del valor de la var 'page', en el query-string de la url,
        //la primera vez que se accede a index $pagina_actual es null, porque $_GET está vacio
        $pagina_actual = $_GET['page'];
        //filtra para que el valor de $pagina_actual sea un entero válido
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        //si no existe página_actual (null) o su valor es < 1
        if(!$pagina_actual || $pagina_actual < 1) {
            //la primera vez que se accede a la función index(), $pagina_actual es null,
            //porque el arreglo $_GET está vacio
            //redirecciona a la url y genera el query-string con la var page=1
            header('Location: /admin/registrados?page=1');
        }
        //var con la cantidad de registros que se mostrarán por página
        $registros_por_pagina = 5;
        //llama método total() que obtiene la cantidad total de registros de la tabla registros
        $total = Registro::total();

        //obtiene objeto con el modelo Paginacion, enviando argumentos.
        //Este objeto se requerirá para obtener el offset de la paginación
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        //validación, evita url con ?page= a una página mayor al total de paginas necesarias.
        //Si total_paginas necesarias es < al número de la $pagina_actual:
        if($paginacion->total_paginas() < $pagina_actual) {
            //redirecciona a la url de la página 1
            header('Location: /admin/registrados?page=1');
        }

        //obtiene los registros por página de la DB, con el método paginar(), que requiere
        //la cantidad de registros por página que quereamos mostrar y el offset, obtenido con el método offgset()
        //El offset es la cantidad de registros a saltar del total, según  los registros por pagina que queramos mostrar y la página a mostrar
        $registros = Registro::paginar($registros_por_pagina, $paginacion->offset());

        //itera los registros y por cada registro:
        foreach($registros as $registro) {
            //busca con el modelo Usuario (tabla usuarios), el usuario cuyo id es igual al id de $registro->usuario_id y 
            //asigna el objeto encontrado, a la propiedad usuario del objeto $registro
            $registro->usuario = Usuario::find($registro->usuario_id);
            //busca con el modelo Paquete (tabla paquetes), el paquete cuyo id es igual al id de $registro->paquete_id y
            //asigna el objeto encontrado, a la propiedad paquete del objeto $registro 
            $registro->paquete = Paquete::find($registro->paquete_id);
        }

    

        $router->render('admin/registrados/index', [
            'titulo' => 'Usuarios Registrados',
            'registros' => $registros,
            'paginacion' => $paginacion->paginacion()
        ]);
    }
}