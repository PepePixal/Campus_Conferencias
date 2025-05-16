<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Resetea tu Password</p>

    <?php 
        require_once __DIR__ .  '/../templates/alertas.php';
    ?>

    <!-- como recibe $token_valido, enviado por el render del AuthController,
    si el token es válido, mustra el form que soliciata el nuevo password -->
    <?php 
        if($token_valido) {
    ?>
            <form class="formulario" method="POST">
                
                <div class="formulario__campo">
                    <label class="formulario__label" for="password">Nuevo Password</label>
                    <input 
                    class="formulario__input"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Tu Nuevo Password"
                    />    
                </div>

                <input type="submit" class="formulario__submit" value="Guardar Password">
            </form>
    <?php
        }
    ?>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? Inicia Sesión</a>
        <a href="/olvide" class="acciones__enlace">¿Olvidé mi Password?</a>
    </div>
</main>