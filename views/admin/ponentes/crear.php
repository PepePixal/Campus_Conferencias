<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<!-- botón Añadir ponente -->
<div class="dashboard__contenedor-boton">
    <a href="/admin/ponentes" class="dashboard__boton">
        <i class="fa-solid fa-circle-arrow-left"></i>
        Volver
    </a>
</div>

<div class="dashboard__formulario">
    <?php 
        include_once __DIR__ . '../../../templates/alertas.php';
    ?>

    <form method="POST" action="/admin/ponentes/crear" enctype="multipart/form-data" class="formulario" >

        <?php 
        include_once __DIR__ . '/formulario.php';
        ?>

        <input class="formulario__submit formulario__submit--registrar" type="submit" value="Registrar Ponente">
    </form>

</div>
