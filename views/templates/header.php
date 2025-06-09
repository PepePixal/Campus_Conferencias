<header class="header">
    <div class="header__contenedor">
        <nav class="header__navegacion">

            <!-- valida si el usuario está logueado o autenticado -->
            <?php if(is_auth()) { ?>
                <!-- valida si el usuario logueado es tipo admin -->
                <a href=<?php echo is_admin() ? '/admin/dashboard' : '/finalizar-registro'; ?> class="header__enlace">Administrar</a>
                <form method="POST" action="/logout" class="header__form">
                    <input type="submit" value="Cerrar Sesión" class="header__submit">
                </form>

            <?php } else { ?>

                <a href="/registro" class="header__enlace">Registro</a>
                <a href="/login" class="header__enlace">Iniciar Sesión</a>

            <?php  } ?>


        </nav>

        <div class="header__contenido">
            <a href="/"><h1 class="header__logo">&#60;CampusDevWeb /></h1></a>

            <p class="header__texto">5 y 6 / Octubre / 2025</p>
            <p class="header__texto header__texto--modalidad">En línea - Presencial</p>

            <a href="/registro" class="header__boton">Comprar Pase</a>
        </div>
    </div>
</header>

<div class="barra">
    <div class="barra__contenido">
        <a href="/"><h3 class="barra__logo">&#60;CampusDevWeb /></h3></a>
        <nav class="navegacion">
            <!-- con php, si el método pagina_actual() retorna true, ? agrega navegacion__enlace--actual a la clase,  si no : no agrega nada -->
            <a href="/campusdevweb" class="navegacion__enlace <?php echo pagina_actual('/campusdevweb') ? 'navegacion__enlace--actual' : ''; ?>">Evento</a>
            <a href="/precios" class="navegacion__enlace <?php echo pagina_actual('/precios') ? 'navegacion__enlace--actual' : ''; ?>">Precios</a>
            <a href="/talleres-conferencias" class="navegacion__enlace <?php echo pagina_actual('/talleres-conferencias') ? 'navegacion__enlace--actual' : ''; ?>">Talleres / Conferencias</a>
            <a href="/registro" class="navegacion__enlace <?php echo pagina_actual('/registro') ? 'navegacion__enlace--actual' : ''; ?>">Comprar Pase</a>
        </nav>
    </div>
</div>