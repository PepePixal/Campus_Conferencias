<?php

namespace Controllers;

use MVC\Router;
use Model\Ponente;
use Intervention\Image\ImageManagerStatic as Image;

class PonentesController {

    public static function index(Router $router) {
        //optiene arreglo de objetos, con todos los ponentes de la DB, 
        //con el método all() exntendido en model Ponente, de model ActiveRecord
        $ponentes =Ponente::all();

        
        //Enviar la vista y datos, al render()
        $router->render('admin/ponentes/index', [
            'titulo' => 'Ponentes / Conferencias',
            'ponentes' => $ponentes
        ]);
    }

    public static function crear(Router $router) {

        $alertas = [];
        //nueva instancia del objeto modelo Ponente.
        $ponente = new Ponente;

         if($_SERVER['REQUEST_METHOD'] === 'POST') {

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

            //sincroniza los atributos del objeto $ponente con los datos del
            //arreglo asoc. enviados con el formulario, en $_POST
            $ponente->sincronizar($_POST);

            //validar los atributos del objeto $ponente sincronizado,
            //retorna arreglo con alertas de validación, si las hay
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
            'alertas' => $alertas
        ]);
    }

}