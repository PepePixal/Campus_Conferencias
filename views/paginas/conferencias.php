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
                
                <!-- inserta el código de template/evento.php -->
                <?php include __DIR__ . '../../templates/evento.php'; ?>

            <?php } ?>
        </div>
        <!-- clases requeridas por Navigation Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <p class="eventos__fecha">Sábado</p>

    <!-- Conferencias del viernes, obtenidos de la DB y recibidas en $eventos -->
    <div class="eventos__listado slider swiper">
        <!-- swiper requiere este div con esta class para dar estilos con swiper al contenido del div-->
        <div class="swiper-wrapper">
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['conferencias_s'] as $evento) { ?>
                
                <!-- inserta el código de template/evento.php -->
                <?php include __DIR__ . '../../templates/evento.php'; ?>

            <?php } ?>
        </div>
        <!-- clases requeridas por Navigation Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>


<div class="eventos eventos--workshops">
    <h3 class="eventos__heading">&lt;Talleres /></h3>

    <p class="eventos__fecha">Viernes</p>

    <!-- Conferencias del viernes, obtenidos de la DB y recibidas en $eventos -->
    <div class="eventos__listado slider swiper">
        <!-- swiper requiere este div con esta class para dar estilos con swiper al contenido del div-->
        <div class="swiper-wrapper">
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['workshops_v'] as $evento) { ?>
                
                <!-- inserta el código de template/evento.php -->
                <?php include __DIR__ . '../../templates/evento.php'; ?>

            <?php } ?>
        </div>
        <!-- clases requeridas por Navigation Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <p class="eventos__fecha">Sábado</p>

    <!-- Conferencias del viernes, obtenidos de la DB y recibidas en $eventos -->
    <div class="eventos__listado slider swiper">
        <!-- swiper requiere este div con esta class para dar estilos con swiper al contenido del div-->
        <div class="swiper-wrapper">
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['workshops_s'] as $evento) { ?>
                
                <!-- inserta el código de template/evento.php -->
                <?php include __DIR__ . '../../templates/evento.php'; ?>

            <?php } ?>
        </div>
        <!-- clases requeridas por Navigation Swiper -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>

</main>