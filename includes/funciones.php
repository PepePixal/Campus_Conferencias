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