<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<!-- botón Añadir ponente -->
<div class="dashboard__contenedor-boton">
    <a href="/admin/ponentes/crear" class="dashboard__boton">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Ponente
    </a>
</div>

<!-- listado de ponentes en una tabla-->
<div class="dashboard__contenedor">
    <!-- si $ponentes, recibido del render del controller, no está vacio -->
     <?php if(!empty($ponentes)) { ?>
        <!-- definición de la tabla -->
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">Ubicación</th>
                    <th scope="col" class="table__th"></th>
                </tr>
            </thead>

            <body class="table__tbody">
                <!-- itera los $ponentes y para cada $ponente: -->
                <?php foreach($ponentes as $ponente) { ?>
                    <tr class="table__tr">
                        <td class="table__td">
                            <?php echo $ponente->nombre . " " . $ponente->apellido; ?>    
                        </td>
                        <td class="table__td">
                            <?php echo $ponente->ciudad . ", " . $ponente->pais; ?>    
                        </td>

                        <!-- enlace a endpoint enviando el id del ponenete en la url con ?id= -->
                        <td class="table__td--acciones">
                            <a class="table__accion table__accion--editar" href="/admin/ponentes/editar?id=<?php echo $ponente->id; ?>">
                                <i class="fa-solid fa-user-pen"></i>
                                Editar
                            </a>
                            <!-- formulario con button (permite icono), para eliminar -->
                            <form class="table__formulario">
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
        <p class="text-center">No existen Ponentes, todavía.</p>
    <?php } ?>
</div>