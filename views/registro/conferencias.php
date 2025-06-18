<h2 class="pagina__heading"><?php echo $titulo; ?></h2>
<p class="pagina__descripcion">Elige hasta 5 eventos Presenciales</p>

<div class="eventos-registro">
    <main class="eventos-registro__listado">
        <h3 class="eventos-registro__heading--conferencias">&lt;Conferencias /></h3>
        <p class="eventos-registro__fecha">Viernes</p>

        <div class="eventos-registro__grid">
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['conferencias_v'] as $evento) { ?>
                <!-- inserta el código de la plantilla views/registro/evento.php -->
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>

        <p class="eventos-registro__fecha">Sábado</p>

        <div class="eventos-registro__grid">
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['conferencias_s'] as $evento) { ?>
                <!-- inserta el código de la plantilla views/registro/evento.php -->
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>

        <h3 class="eventos-registro__heading--workshops">&lt;Workshops /></h3>
        <p class="eventos-registro__fecha">Viernes</p>

        <div class="eventos-registro__grid eventos--workshops" >
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['workshops_v'] as $evento) { ?>
                <!-- inserta el código de la plantilla views/registro/evento.php -->
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>

        <p class="eventos-registro__fecha">Sábado</p>

        <div class="eventos-registro__grid eventos--workshops">
            <!-- iterea los objetos del arreglo ['conferencias_v'] del arreglo $eventos -->
            <?php foreach($eventos['workshops_s'] as $evento) { ?>
                <!-- inserta el código de la plantilla views/registro/evento.php -->
                <?php include __DIR__ . '/evento.php'; ?>
            <?php } ?>
        </div>
    </main>

    <aside class="registro">
        <h3 class="registro__heading">Tu Registro</h3>
        <!-- contenido para este div, desde JS -->
        <div id="registro-resumen" class="registro-resumen"></div>

        <div class="registro__regalo">
            <label for="regalo" class="registro__label">Selecciona un regalo</label>
            <select id="regalo" class="registro__select">
                <option value="">-- Selecciona tu regalo --</option>
                <!-- itera los regalos obtenidos de la tabla, para mostrarlos como opciones -->
                <?php foreach($regalos as $regalo) { ?>
                    <!-- por cada regalo una opción -->
                    <option value="<?php echo $regalo->id; ?>"><?php echo $regalo->nombre; ?></option>
                <?php } ?>
            </select>
        </div>

        <form id="registro" class="formulario">
            <div class="formulario__campo">
                <input type="submit" class="formulario__submit formulario__submit--full" value="Registrarme">
            </div>
        </form>
    </aside>
</div>