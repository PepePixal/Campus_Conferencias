<?php
    //alertas.php tiene acceso al arreglo asociativo $alertas.
    //Itera el arreglo asoc $alertas, en cada iteración asigna:
    // a $key, la clave del elemento iterado y a $alerta, el valor del elemento (arreglo)
    foreach ($alertas as $key => $alerta) {
        //en cada iteración del arreglo asoc $alertas, obtiene un nuevo arreglo $alerta y
        //itera el arreglo index $alerta, asignando a $mensaje el valor del elemento iterado (el mensaje)
        foreach($alerta as $mensaje) {
?>

            <!-- por cada mensaje obtenido de la iteración, genera un div cuya clase
            contiene 'alerta' y alerta__ y el valor de la clave en $key ('error' o 'exito'),
            //la clase alerta__ servirá para aplicar diferentes estilos css, según sea error o exito-->
            <div class="alerta alerta__<?php echo $key; ?> ">
                <!-- muestra el mensaje obtenido y asignado a la var $mensaje -->
                <?php echo $mensaje; ?>
            </div>
<?php
        } 
    }
?>