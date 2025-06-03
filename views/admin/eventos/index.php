<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<!-- botón Añadir evento -->
<div class="dashboard__contenedor-boton">
    <a href="/admin/eventos/crear" class="dashboard__boton">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Evento
    </a>
</div>

<!-- listado de eventos en una tabla-->
<div class="dashboard__contenedor">
    <!-- si $eventos, recibido del render del controller, no está vacio -->
     <?php if(!empty($eventos)) { ?>
        <!-- definición de la tabla -->
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Evento</th>
                    <th scope="col" class="table__th">Categoría</th>
                    <th scope="col" class="table__th">Día y Hora</th>
                    <th scope="col" class="table__th">Ponente</th>
                    <th scope="col" class="table__th"></th>
                </tr>
            </thead>

            <body class="table__tbody">
                <!-- itera los $ponentes y para cada $ponente: -->
                <?php foreach($eventos as $evento) { ?>
                    <tr class="table__tr">
                        <td class="table__td">
                            <?php echo $evento->nombre; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->categoria->nombre; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->dia->nombre . ", " . $evento->hora->hora; ?>
                        </td>
                        <td class="table__td">
                            <?php echo $evento->ponente->nombre . " " . $evento->ponente->apellido; ?>
                        </td>

                        <!-- enlace a endpoint enviando el id del evento en la url con ?id= -->
                        <td class="table__td--acciones">
                            <a class="table__accion table__accion--editar" href="/admin/eventos/editar?id=<?php echo $evento->id; ?>">
                                <i class="fa-solid fa-pencil"></i>
                                Editar
                            </a>

                            <!-- formulario con button (permite icono), para eliminar -->
                            <form method="POST" action="/admin/eventos/eliminar" class="table__formulario">
                                <input type="hidden" name="id" value="<?php echo $evento->id; ?>">
                                <button class="table__accion table__accion--eliminar" type="submit">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
            </body>
        </table>

    <?php } else { ?>
        <p class="text-center">No existen Eventos, todavía.</p>
    <?php } ?>
</div>

<!-- inyecta el código html generado en método paginacion() de la clase Paginacion.html -->
<?php
    echo $paginacion;
?>