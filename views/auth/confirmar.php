<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Tu Cunenta CampusDevWeb</p>

    <?php
        require_once __DIR__ . '/../templates/alertas.php';
    ?>

    <!-- si la alerta recibida en $lertas tiene la llave ['exito'],
     isset() retorna true, entonces muestra Iniciar Sesión -->
    <?php 
        if(isset($alertas['exito'])) {
    ?>
            <div class="acciones--centrar">
                <a href="/login" >Iniciar Sesión</a>
            </div>
    <?php 
        }
    ?>
</main>