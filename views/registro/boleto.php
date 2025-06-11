<main class="pagina">
    <h2 class="pagina__heading"><?php echo $titulo; ?></h2>
    <p class="pagina__descripcion">Tu ticket Virtual de Incripción</p>

    <div class="boleto-virtual">
        <!-- la clase boleto le da el estilo css de ticket -->
        <!-- y agrega clase boleto-- y el nombre del paquete, obtenido del objeto $registro --> 
        <div class="boleto boleto--<?php echo strtolower($registro->paquete->nombre); ?>">
            <div class="boleto__contenido">
                <h4 class="boleto__logo">&#60;CampusDevWeb /></h4>
                <p class="boleto__plan"><?php echo $registro->paquete->nombre; ?></p>
                <p class="boleto__nombre"><?php echo $registro->usuario->nombre . " " . $registro->usuario->apellido; ?></p>
            </div>
            <p class="boleto__codigo"><?php echo '#' . $registro->token; ?></p>
        </div>

    </div>
</main>