<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Inciar sesión en Campus</p>

    <?php
        require_once __DIR__ . '/../templates/alertas.php';
    ?>

    <form class="formulario" method="POST" action='/login'>
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input 
                class="formulario__input"
                type="email"
                id="email"
                name="email"
                placeholder="Tu Email"
            />    
        </div>
    
        <div class="formulario__campo">
            <label class="formulario__label" for="email">Password</label>
            <input 
            class="formulario__input"
            type="password"
            id="password"
            name="password"
            placeholder="Tu Password"
            />    
        </div>
        
        <input type="submit" class="formulario__submit" value="Iniciar Sesión">
    </form>

    <div class="acciones">
        <a href="/registro" class="acciones__enlace">¿No tienes cuenta? Creala</a>
        <a href="/olvide" class="acciones__enlace">¿Olvidé mi Password?</a>
    </div>
</main>