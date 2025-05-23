<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

//función que recibe una ruta $path y comprueba si el valor string de la ruta actual, 
//obtenida de $_SERVER['PATH_INF], contiene el valor string de $path, recibido como argumento,
//retorna un : bool: Si es que si ? retorna true (1), de lo contrario : retorna false (nada);
function pagina_actual ($path) : bool{
    return str_contains( $_SERVER['PATH_INFO'], $path) ? true : false;
}; 

//comprueba si hay un usuario autenticado en la sesión. Retorna bool
function is_auth() : bool {
    //inicia sesión
    session_start();
    //comprueba si el elemento 'nombre' es diferente a null y no está vacio,
    //retorna true o false
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}

//comprueba si el usuario es tipo administrador. Retorna bool
function is_admin() : bool {
    //si la sesión no está iniciada, la inicia
    if(!isset($_SESSION)) {
        session_start();
    }
    //comprueba si el elemento admin es difrente null y no está vacio,
    //retorna true o false
    return isset($_SESSION['admin']) && !empty($_SESSION['admin']);
}

