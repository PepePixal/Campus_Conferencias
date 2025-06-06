<main class="agenda">
<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>
<p class="agenda__descripcion">Eventos con los mejores expertos en desarrollo web</p>

<div class="eventos">
    <h3 class="eventos__heading">&lt;Conferencias /></h3>
    <p class="eventos__fecha">Viernes</p>

    <!-- Conferencias del viernes, obtenidos de la DB y recibidas en $eventos -->
    <div class="eventos__listado slider swiper">
        <!-- swiper requiere este div con esta class para dar estilos con swiper al contenido del div-->
        <div class="swiper-wrapper">
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['conferencias_v'] as $evento) { ?>
                <!-- swiper requiere esta class swiper-slide para dar estilos con swiper -->
                <div class="evento swiper-slide">
                    <p class="evento__hora"><?php echo $evento->hora->hora; ?></p>

                    <div class="evento__informacion">
                        <h4 class="evento__nombre"><?php echo $evento->nombre; ?></h4>
                        <p class="evento__introduccion"><?php echo $evento->descripcion; ?></p>

                        <div class="evento__autor-info">
                            <picture>
                                <source srcset="img/speakers/<?php echo $evento->ponente->imagen; ?>.webp" type="image/webp">
                                <source srcset="img/speakers/<?php echo $evento->ponente->imagen; ?>.png" type="image/png">
                                <img class=evento__imagen-autor loading="lazy" width="200" height="300" src="img/speakers/<?php echo $evento->ponente->imagen; ?>.png" type="image/png" alt="imagen ponente">
                            </picture>
                            <p class="evento__autor-nombre"><?php echo $evento->ponente->nombre . " " . $evento->ponente->apellido; ?></p>
                        </div>

                    </div>
                </div>

            <?php } ?>
        </div>

        <!-- clases requeridas por Navigation Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <p class="eventos__fecha">Sábado</p>
    <!-- Conferencias del sábado, obtenidos de la DB -->
    <div class="eventos__listado">

    </div>
</div>


<div class="eventos eventos--workshops">
    <h3 class="eventos__heading">&lt;Talleres /></h3>
    <p class="eventos__fecha">Viernes</p>
    <!-- Conferencias del viernes, obtenidos de la DB -->
    <div class="eventos__listado">

    </div>

    <p class="eventos__fecha">Sábado</p>
    <!-- Conferencias del sábado, obtenidos de la DB -->
    <div class="eventos__listado">

    </div>
</div>

</main>