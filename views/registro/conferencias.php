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
    </main>

    <aside class="registro">
        <h3 class="registro__heading">Tu Registro</h3>
    </aside>
</div>