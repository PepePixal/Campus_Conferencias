<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<main class="bloques">
    <div class="bloques__grid">
        <div class="bloque">
            <h3 class="bloque__heading">Últimos Registros</h3>
            <!-- iitera los objetos de registros y por cada objeto: -->
            <?php foreach($registros as $registro) { ?>
                <div class="bloque__contenido">
                    <p class="bloque__texto">
                        <?php echo $registro->usuario->nombre . " " . $registro->usuario->apellido; ?>
                    </p>
                </div>
            <?php } ?>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Ingresos</h3>
            <p class="bloque__texto--cantidad">
                <?php echo $ingresos . " €"; ?>
            </p>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Eventos con menos Plazas disponibles</h3>
            <!-- iitera los objetos de menos_plazas y por cada objeto: -->
            <?php foreach($menos_disponibles as $evento) { ?>
                <div class="bloque__contenido">
                    <p class="bloque__texto">
                        <?php echo $evento->nombre . " - " . $evento->disponibles . " Disponibles"; ?>
                    </p>
                </div>
            <?php } ?>
        </div>

        <div class="bloque">
            <h3 class="bloque__heading">Eventos con más Plazas disponibles</h3>
            <!-- iitera los objetos de menos_plazas y por cada objeto: -->
            <?php foreach($mas_disponibles as $evento) { ?>
                <div class="bloque__contenido">
                    <p class="bloque__texto">
                        <?php echo $evento->nombre . " - " . $evento->disponibles . " Disponibles"; ?>
                    </p>
                </div>
            <?php } ?>
        </div>

    </div>
</main>