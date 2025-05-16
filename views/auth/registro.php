<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo; ?></h2>
    <p class="auth__texto">Regístrate en el Campus</p>

    <?php 
        require_once __DIR__ .  '/../templates/alertas.php';
    ?>

    <form class="formulario" method="POST" action="/registro">
        <div class="formulario__campo">
            <label class="formulario__label" for="nombre">Nombre</label>
            <input 
                class="formulario__input"
                type="text"
                id="nombre"
                name="nombre"
                placeholder="Tu Nombre"
                value="<?php echo $usuario->nombre; ?>"
            />    
        </div>
        
        <div class="formulario__campo">
            <label class="formulario__label" for="apellido">Apellido</label>
            <input 
                class="formulario__input"
                type="text"
                id="apellido"
                name="apellido"
                placeholder="Tu Apellido"
                value="<?php echo $usuario->apellido; ?>"
            />    
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="email">Email</label>
            <input 
                class="formulario__input"
                type="email"
                id="email"
                name="email"
                placeholder="Tu Email"
                value="<?php echo $usuario->email; ?>"
            />    
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="password">Password</label>
            <input 
            class="formulario__input"
            type="password"
            id="password"
            name="password"
            placeholder="Tu Password"
            />    
        </div>

        <div class="formulario__campo">
            <label class="formulario__label" for="password2">Repetir Password</label>
            <input 
            class="formulario__input"
            type="password"
            id="password2"
            name="password2"
            placeholder="Repetir Password"
            />    
        </div>
        
        <input type="submit" class="formulario__submit" value="Crear Cuenta">
    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes cuenta? Inicia Sesión</a>
        <a href="/olvide" class="acciones__enlace">¿Olvidé mi Password?</a>
    </div>
</main>