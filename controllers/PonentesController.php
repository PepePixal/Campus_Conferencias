<?php

namespace Controllers;

use MVC\Router;
use Model\Ponente;
use Classes\Paginacion;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController {

    public static function index(Router $router) {

        //**Paginación
        //obtiene la página actual, del valor de la var 'page', en el query-string de la url,
        //la primera vez que se accede a index $pagina_actual es null, porque $_GET está vacio
        $pagina_actual = $_GET['page'];
        //filtra para que el valor de $pagina_actual sea un entero válido
        $pagina_actual = filter_var($pagina_actual, FILTER_VALIDATE_INT);
        //si no existe página_actual (null) o su valor es < 1
        if(!$pagina_actual || $pagina_actual < 1) {
            //la primera vez que se accede a la función index(), $pagina_actual es null,
            //porque el arreglo $_GET está vacio
            //redirecciona a la url y genera el query-string con la var page
            header('Location: /admin/ponentes?page=1');
        }
        //var con la cantidad de registros que se mostrarán por página
        $registros_por_pagina = 5;
        //llama método total() que obtiene la cantidad total de registros de la tabla
        $total =Ponente::total();

        //instancia de nuevo objeto Paginación, enviando parámetros
        $paginacion = new Paginacion($pagina_actual, $registros_por_pagina, $total);

        //validación, evita url con ?page= a una página mayor al total de paginas necesarias.
        //Si total_paginas necesarias es < al número de la $pagina_actual:
        if($paginacion->total_paginas() < $pagina_actual) {
            //redirecciona a la url de la página 1
            header('Location: /admin/ponentes?page=1');
        }

        //llama método paginar, que obtiene de la DB, la cantidad de ponentes en $registros_por_pagina,
        //saltando la cantidad de ponentes obtenida con el método offset() 
        $ponentes = Ponente::paginar($registros_por_pagina, $paginacion->offset());

        //comprueba si el usuario no es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
        }

        //Enviar la vista y datos, al render()
        $router->render('admin/ponentes/index', [
            'titulo' => 'Ponentes / Conferencias',
            'ponentes' => $ponentes,
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
        //nueva instancia del objeto modelo Ponente.
        $ponente = new Ponente;

         if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //comprueba si el usuario no es tipo admin redirege a login
            if(!is_admin()) {
                header('Location: /login');
            }

            //$_FILES, arreglo asoc con la info de los archivos subidos con form html y POST,
            //['imagen'] es el valor del atributo name, del input donde ponemos el archivo imagen,
            //['tmp_name'] contiene la ruta temporal, donde está almacenado el archivo
            //Comprobar que existe una imagen o archivo
            if(!empty($_FILES['imagen']['tmp_name'])) {

                //define carpeta para imágenes y la asigna a $carpeta_imagenes
                $carpeta_imagenes = '../public/img/speakers';

                //si no existe la carpeta (directorio), la crea
                if(!is_dir($carpeta_imagenes)) {
                    //0755 permiso de acceso a la carpeta, true permite subdirectorios
                    mkdir($carpeta_imagenes, 0755, true);
                }

                //Generar las versiones .PNG (transparente) y WebP, a partir de la imagen subida con el form.
                //Requiere importar la clase Image de Intervention\Image\Image y modificar
                //el use - use Intervention\Image\ImageManagerStatic as Image;
                //Los archivos de imágenes quedan en memoria hasta que se guarden en la carpeta.
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80);

                //genera un nombre aleatório y lo asigna a $nombre_imagen
                $nombre_imagen = md5( uniqid( rand(), true ));

                //agrega el nombre_imagen al elemento 'imagen' del arreglo $_POST del form.
                $_POST['imagen'] = $nombre_imagen;

            }

            //el elemento 'redes' del arreglo en $_POST, es un arreglo que contiene las urls de las redes sociales, 
            //se transforma a json, eliminando antes los SLASHES (\) de las urls,
            //para poder sincronizarlo con el modelo $ponente
            $_POST['redes'] = json_encode( $_POST['redes'], JSON_UNESCAPED_SLASHES);

            //sincroniza las propiedades del objeto $ponente con los datos del
            //arreglo asoc. enviados con el formulario, en $_POST
            $ponente->sincronizar($_POST);

            //validar las propiedades del objeto $ponente sincronizado.
            //Retorna arreglo con alertas de validación, si las hay
            $alertas = $ponente->validar();

            //si no hay alertas, se ha pasado la validación del formulario
            if(empty($alertas)) {

                //guardar la imagenes en memoria, en la misma carpeta del servidor,
                //con el mismo nombre generado aleatóriamente pero con su própia extensión.
                $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");

                //guardar los datos del objeto $ponente, en la DB. Retorna bool
                $resultado = $ponente->guardar();

                //si se ha guadado correctamente, true
                if($resultado) {
                    //redirigir al endpoing
                    header('Location: /admin/ponentes');
                }
            }
         }

        $router->render('admin/ponentes/crear', [
            'titulo' => 'Registrar Ponentes',
            'ponente' => $ponente,
            'alertas' => $alertas,
            //json_decode() convierte el string de $ponente->redes,
            //en un objeto de claves y valores, con las redes sociales 
            'redes' => json_decode($ponente->redes)
        ]);
    }

    public static function editar(Router $router) {
        //comprueba si el usuario no es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
        }

        //define arreglo para almacenar las alertas
        $alertas = [];

        //obtener el id de la superglobal $_GET,
        //recibido en la url .../admin/ponentes/editar?id= ,
        //enviada por el enlace editar de editar ponentes.
        $id = $_GET['id'];

        //validar que el id sea un valor tipo entero,
        //si es un entero lo retorna, si no, retorna false
        $id = filter_var($id, FILTER_VALIDATE_INT);
        //si el $id no existe o no es un entero, (false)
        if(!$id){
            //redirigir al endpoint ponentes
            header('Location: /admin/ponentes');
        }
        
        //Obtener el ponente a editar, por su id
        $ponente = Ponente::find($id);

        //si no existe el $ponente buscado (null)
        if(!$ponente) {
            //redirigir al endpoint ponentes
            header('Location: /admin/ponentes');
        }

        //asigna a la propiedad imagen_actual del objeto $ponente,
        //el valor de la propiedad imagen
        $ponente->imagen_actual = $ponente->imagen;

        //cuando se envía el formulario editar via POST, para actualizar
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            //comprueba si el usuario no es tipo admin redirege a login
            if(!is_admin()) {
                header('Location: /login');
            }

            //** Comprobar si se ha subido una imagen nueva, al formulaio enviado
            //$_FILES, arreglo asoc con la info de los archivos subidos con form html y POST,
            //['imagen'] es el valor del atributo name, del input donde ponemos el archivo imagen,
            //['tmp_name'] contiene la ruta temporal, donde está almacenado el archivo
            //Comprobar que existe una imagen o archivo
            if(!empty($_FILES['imagen']['tmp_name'])) {

                //define carpeta para imágenes y la asigna a $carpeta_imagenes
                $carpeta_imagenes = '../public/img/speakers';

                //si no existe la carpeta (directorio), la crea
                if(!is_dir($carpeta_imagenes)) {
                    //0755 permiso de acceso a la carpeta, true permite subdirectorios
                    mkdir($carpeta_imagenes, 0755, true);
                }

                //Generar las versiones .PNG (transparente) y WebP, a partir de la imagen subida con el form.
                //Requiere importar la clase Image de Intervention\Image\Image y modificar
                //el use - use Intervention\Image\ImageManagerStatic as Image;
                //Los archivos de imágenes quedan en memoria hasta que se guarden en la carpeta.
                $imagen_png = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('png', 80);
                $imagen_webp = Image::make($_FILES['imagen']['tmp_name'])->fit(800,800)->encode('webp', 80);

                //genera un nombre aleatório y lo asigna a $nombre_imagen
                $nombre_imagen = md5( uniqid( rand(), true ));

                //agrega el nombre_imagen al elemento 'imagen' del arreglo $_POST del form.
                $_POST['imagen'] = $nombre_imagen;

            //si no viene una nueva imagen del formulario actualizar
            } else {
                //asigna a la propiedad 'imagen' de $_POST, el nombre de la imagen que ya existía
                $_POST['imagen'] = $ponente->imagen_actual;
            };

            //el elemento 'redes' del arreglo en $_POST, es un arreglo que contiene las urls de las redes sociales, 
            //se transforma a json, eliminando antes los SLASHES (\) de las urls,
            //para poder sincronizarlo con el modelo $ponente
            $_POST['redes'] = json_encode( $_POST['redes'], JSON_UNESCAPED_SLASHES);

            //Sincronizar el modelo $ponente con los elementos del $_POST del form,
            //ya sea con el nombre de la imagen nueva o con el de la imagen que ya existía
            $ponente->sincronizar($_POST);

            //validar los atributos del objeto $ponente sincronizado,
            //retorna arreglo con alertas de validación, si las hay
            $alertas = $ponente->validar();

             //si no hay alertas, se ha pasado la validación del formulario
            if(empty($alertas)) {
                //comprobar si hay una nueva imagen, 
                //comprobando si se ha generado aleatoriamente un $nombre_imagen
                if(isset($nombre_imagen)) {
                    //guardar las nuevas imagenes (en memoria), en la misma carpeta del servidor,
                    //con el mismo nombre generado aleatóriamente, pero con su própia extensión.
                    $imagen_png->save($carpeta_imagenes . '/' . $nombre_imagen . ".png");
                    $imagen_webp->save($carpeta_imagenes . '/' . $nombre_imagen . ".webp");
                }

                //guardar los datos actualizados del ponente, en la DB
                $resultado = $ponente->guardar();

                //si se ha guadado correctamente, true
                if($resultado) {
                    //redirigir al endpoing
                    header('Location: /admin/ponentes');
                }
            }
        }
        
        //Enviar la vista y datos, al render()
        $router->render('admin/ponentes/editar', [
            'titulo' => 'Editar Ponente',
            'alertas' => $alertas,
            'ponente' => $ponente,
            //json_decode() convierte el string de $ponente->redes,
            //en un objeto de claves y valores, con las redes sociales 
            'redes' => json_decode($ponente->redes)
        ]);
    }

    public static function eliminar() {
        //comprueba si el usuario no es tipo admin redirege a login
        if(!is_admin()) {
            header('Location: /login');
        }

        //si la consulta al servidor es con el método POST
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['id'];

            //obtener el ponente por su id
            $ponente = Ponente::find($id);

            //si ponente no ! esta declarado o no es diferente de null
            if(!isset($ponente)) {
                //redirigir al panel de ponentes
                header('Location: /admin/ponentes');
            }

            //eliminar el ponente y obtener el resultado bool
            $resultado = $ponente->eliminar();

            //si el resultado de eliminar es true
            if($resultado) {
                //redirigir al panel de ponentes
                header('Location: /admin/ponentes');
            }
        }
    }

}