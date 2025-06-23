<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<!-- listado de usuarios registrados en una tabla-->
<div class="dashboard__contenedor">
    <!-- si $ponentes, recibido del render del controller, no está vacio -->
     <?php if(!empty($registros)) { ?>
        <!-- definición de la tabla -->
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">Email</th>
                    <th scope="col" class="table__th">Plan</th>
                    <th scope="col" class="table__th"></th>
                </tr>
            </thead>

            <body class="table__tbody">
                <!-- itera los $ponentes y para cada $ponente: -->
                <?php foreach($registros as $registro) { ?>
                    <tr class="table__tr">
                        <td class="table__td">

                            <?php echo $registro->usuario->nombre . " " . $registro->usuario->apellido; ?>    
                        </td>
                        <td class="table__td">

                            <?php echo $registro->usuario->email; ?>    
                        </td>
                        <td class="table__td">

                            <?php echo $registro->paquete->nombre; ?>    
                        </td>
                       
                    </tr>
                <?php } ?>
            </body>
        </table>

    <?php } else { ?>
        <p class="text-center">No existen Registros, todavía.</p>
    <?php } ?>
</div>

<!-- inyecta el código html generado en método paginacion() de la clase Paginacion.html -->
<?php
    echo $paginacion;
?>
