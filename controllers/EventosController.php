<?php

namespace Controllers;

use Model\Dia;
use Model\Hora;
use MVC\Router;
use Model\Evento;
use Model\Ponente;
use Model\Categoria;
use Classes\Paginacion;

class EventosController {

    public static function index(Router $router) {

        //comprueba si el usuario no es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
        }

        //**Paginación
        //obtiene la página actual, del valor de la var 'page', en el query-string de la url,
        //la primera vez que se accede a index $pagina_actual es null, porque $_GET está vacio
        $pagina_actual = $_GET['page'];
        //filtrar que contiene un número entero. Retorna o el valor o False
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        
        //validar si $pagina_actual no existe (null) o su valor es < 1
        if(!$pagina_actual || $pagina_actual < 1) {
            //redireccionar a la primera página de la paginación
            header('Location: /admin/eventos?page=1');
        }

        //eventos a mostrar por página
        $por_pagina = 10;
        //obtiene la cantidad total de eventos en la DB, con el modelo Evento
        $total = Evento::total();

        //obtiene objeto con el modelo Paginacion, enviando argumentos.
        //Este objeto se requerirá para obtener el offset de la paginación y 
        //para enviar al render.
        $paginacion = new Paginacion($pagina_actual, $por_pagina, $total);

        //obtiene los eventos por página de la BD, con el método paginar(), que requiere
        //la cantidad de ponentes por página que quereamos mostrar y el offset, obtenido con el método offgset()
        //El offset es la cantidad de registros a saltar del total, según los registros que queramos mostrar y la página a mostrar
        $eventos = Evento::paginar($por_pagina, $paginacion->offset());

        // Creamos un array de objetos stdClass para la vista
        $eventosView = [];

        foreach($eventos as $evento) {
            // Se crea un objeto genérico (stdClass) que servirá como contenedor de datos para la vista.
            // Así evitamos modificar directamente el modelo Evento y solo pasamos la información necesaria.
            $eventoView = new \stdClass();

            //obtiene el id del evento y lo asigna a $eventoView
            $eventoView->id = $evento->id;
            // Obtiene el nombre del evento y lo asigna a $eventosView
            $eventoView->nombre = $evento->nombre;
            // Busca la categoría correspondiente al evento actual usando su ID y la asigna al objeto de la vista.
            // Esto permite acceder a los datos completos de la categoría (el nombre de la categoría) en la vista.
            $eventoView->categoria = Categoria::find($evento->categoria_id);
            // Busca el día correspondiente al evento actual usando su ID y asigna el nombre al objeto de la vista.
            $eventoView->dia = Dia::find($evento->dia_id);
            $eventoView->hora = Hora::find($evento->hora_id);
            $eventoView->ponente = Ponente::find($evento->ponente_id);

            //por cada evento, asigna las propiedades y sus valores, al arrglo eventosView[] que pasaremos a la vista
            $eventosView[] = $eventoView;
        }

        $router->render('admin/eventos/index', [
            'titulo' => 'Conferencias y Workshops',
            'eventos' => $eventosView,
            //envia al render, el código html retornado por el método paginacion()
            //sobre el objeto $paginacion, del modelo Paginacion
            'paginacion' => $paginacion->paginacion()
        ]);
    }

    public static function crear(Router $router) {

        //comprueba si el usuario no es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
        }

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

            //comprueba si el usuario no es tipo admin redirege a login
            if(!is_admin()) {
                header('Location: /login');
            }

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

    public static function editar(Router $router) {

        //comprueba si el usuario no es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
        }

        //define arreglo para almacenar las alertas
        $alertas = [];

        //obtiene el id de $_GET, con los datos recibidos por la URL
        $id = $_GET['id'];
        //filtra para obtener un valor entero válido
        $id = filter_var($id, FILTER_VALIDATE_INT);
        
        //si el $id no existe o no es un entero, (false)
        if(!$id){
            //redirigir al endpoint eventos (index.php)
            header('Location: /admin/eventos');
        }
        
        //obtiene el objeto evento por su id, obtenido de $_GET de la url
        $evento = Evento::find($id);
        //valida, si no existe el $evento buscado:
        if(!$evento) {
            //redirigir al endpoint eventos (index.php)
            header('Location: /admin/eventos');
        }

        //obtiene las categorias de la DB, con el model Categoria
        //envía el orden ASCencente como parámetro. (Conf y Works)
        $categorias = Categoria::all('ASC');
        //obtiene los días de la DB, con el model Dia,
        //envía el orden ASCencente como parámetro. (Sábado y Viernes)
        $dias = Dia::all('ASC');
        //obtiene las horas de la DB, con el model Hora,
        //envía el orden ASCencente como parámetro.
        $horas = Hora::all('ASC');

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //comprueba si el usuario no es tipo admin redirege a login
            if(!is_admin()) {
                header('Location: /login');
            }

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

        $router->render('admin/eventos/editar', [
            'titulo' => 'Editar Evento',
            'alertas' => $alertas,
            'categorias' => $categorias,
            'dias' => $dias,
            'horas' => $horas,
            'evento' => $evento
        ]);
    }

    public static function eliminar() {

        //si la consulta al servidor es con el método POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //comprueba si el usuario no es tipo admin redirege a login
            if(!is_admin()) {
                header('Location: /login');
            }

            //obtiene el id del $_POST, enviado por el form,
            //en el value del input oculto con name='id'
            $id = $_POST['id'];

            //obtener el evento por su id
            $evento = Evento::find($id);

            //si evento no ! esta declarado o no es diferente de null
            if(!isset($evento)) {
                //redirigir al panel de ponentes
                header('Location: /admin/eventos');
            }

            //eliminar el evento y obtener el resultado bool
            $resultado = $evento->eliminar();

            //si el resultado de eliminar es true
            if($resultado) {
                //redirigir al panel de ponentes
                header('Location: /admin/eventos');
            }
        }
    }
}

