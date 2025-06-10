<!-- inserta la página con los sliders, conferencias.php  -->
<?php  
    include_once __DIR__ . '/conferencias.php';
?>

<section class="resumen">
    <div class="resumen__grid">
        <div class="resumen__bloque">
        <p class="resumen__texto resumen__texto--numero"><?php echo $ponentes_total; ?></p>
            <p class="resumen__texto">Ponentes</p>
        </div>
        
        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero"><?php echo $conferencias_total; ?></p>
            <p class="resumen__texto">Conferencias</p>
        </div>

        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero"><?php echo $talleres_total; ?></p>
            <p class="resumen__texto">Talleres</p>
        </div>

        <div class="resumen__bloque">
            <p class="resumen__texto resumen__texto--numero">200</p>
            <p class="resumen__texto">Asistentes</p>
        </div>
    </div>
</section>

<section class="speakers">
    <h2 class="speakers__heading">Ponentes</h2>
    <p class="speakers__descripcion">Conoce a los mejores expertos en desarrollo web</p>

    <div class="speakers__grid">
        <!-- itera los $ponentes recibidos del render y por cada ponente: -->
        <?php foreach($ponentes as $ponente) { ?>
            <div class="speaker">
                <picture>
                    <source srcset="img/speakers/<?php echo $ponente->imagen; ?>.webp" type="image/webp">
                    <source srcset="img/speakers/<?php echo $ponente->imagen; ?>.png" type="image/png">
                    <img class="speaker__imagen" loading="lazy" width="200" height="300" src="img/speakers/<?php echo $ponente->imagen; ?>.png" alt="Imagen Ponente">
                </picture>

                <div class="speaker__informacion">
                    <h4 class="speaker__nombre">
                        <?php echo $ponente->nombre . ' ' . $ponente->apellido; ?>
                    </h4>

                    <p class="speaker__ubicacion">
                        <?php echo $ponente->ciudad . ', ' . $ponente->pais; ?>
                    </p>

                    <nav class="speaker-sociales">
                        <!-- las redes sociales de cada objeto ponente, están en un listado string,
                        para poder itererlas, convierte el listado a un ojeto json $redes -->
                        <?php 
                            $redes = json_decode($ponente->redes);
                        ?>

                        <!-- valida que cada del objeto $redes no venga vacia y pinta un <a href.. -->
                        <?php if(!empty($redes->facebook)) { ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="blank " href="<?php echo $redes->facebook; ?>">
                                <span class="speaker-sociales__ocultar">Facebook</span>    
                            </a>
                        <?php } ?>

                        <?php if(!empty($redes->twitter)) { ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="blank " href="<?php echo $redes->twitter; ?>">
                                <span class="speaker-sociales__ocultar">Twitter</span>    
                            </a>
                        <?php } ?>
                        
                        <?php if(!empty($redes->youtube)) { ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="blank " href="<?php echo $redes->youtube; ?>">
                                <span class="speaker-sociales__ocultar">Youtube</span>    
                            </a>
                        <?php } ?>
                        
                        <?php if(!empty($redes->instagram)) { ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="blank " href="<?php echo $redes->instagram; ?>">
                                <span class="speaker-sociales__ocultar">Instagram</span>    
                            </a>
                        <?php } ?>

                        <?php if(!empty($redes->tiktok)) { ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="blank " href="<?php echo $redes->tiktok; ?>">
                                <span class="speaker-sociales__ocultar">Tiktok</span>    
                            </a>
                        <?php } ?>

                        <?php if(!empty($redes->github)) { ?>
                            <a class="speaker-sociales__enlace" rel="noopener noreferrer" target="blank " href="<?php echo $redes->github; ?>">
                                <span class="speaker-sociales__ocultar">Github</span>    
                            </a>
                        <?php } ?>
                    </nav>

                    <ul class="speaker__listado-skills">
                        <!-- los tags de cada ponente están en un listado string separados por comas,
                        explode toma dos parámetros, el caracter separador (,) y el listado, retorna un arreglo
                        con los elementos del listado, separados --> 
                        <?php
                            $tags = explode(',', $ponente->tags);
                            //itera el arreglo $tags y por cada $tag:
                            foreach($tags as $tag) { ?>
                                <li class="speaker__skill"><?php echo $tag; ?></li>
                        <?php } ?>
                    </ul>

                </div>
            </div>
        <?php } ?>
    </div>
</section>

<div id="map" class="mapa">
    <!-- mapa insertado desde en mapa.js -->
</div>

<section class="boletos">
    <h2 class="boletos__heading">Tickets y Precios</h2>
    <p class="boletos__descripcion">Precios para CampusDevWeb</p>
    
    <div class="boletos__grid">
        <div class="boleto boleto--presencial">
            <h4 class="boleto__logo">&#60;CampusDevWeb/></h4>
            <p class="boleto__plan">Presencial</p>
            <p class="boleto__precio">199 €</p>
        </div>

        <div class="boleto boleto--virtual">
            <h4 class="boleto__logo">&#60;CampusDevWeb/></h4>
            <p class="boleto__plan">Virtual</p>
            <p class="boleto__precio">49 €</p>
        </div>

        <div class="boleto boleto--gratis">
            <h4 class="boleto__logo">&#60;CampusDevWeb/></h4>
            <p class="boleto__plan">Gratis</p>
            <p class="boleto__precio">0 €</p>
        </div>
    </div>

    <div class="boleto__enlace-contenedor">
        <a href=""/precios" class="boleto__enlace">Ver Precios</a>
    </div>
</section>