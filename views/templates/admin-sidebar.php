<aside class="dashboard__sidebar">
    <nav class=dashboard__menu>
        <!-- el segundo valor de class, es el resultado booleano (1 o nada), retornado por la función pagina_actual(),
        si es true ? agrega a la clase el valor 'dashboard__enlace--actual, de lo contrario : no gregar nada ''.
        el valor agregado usaremos para dejar resaltado el enlace que corresponda a la página actual, con css -->
        <a href="/admin/dashboard" class="dashboard__enlace <?php echo pagina_actual('/dashboard') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-house dashboard__icono"></i>
            <span class="dashboard__menu-texto">Inicio</span>
        </a>
        <a href="/admin/ponentes" class="dashboard__enlace <?php echo pagina_actual('/ponentes') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-microphone dashboard__icono"></i>
            <span class="dashboard__menu-texto">Ponentes</span>
        </a>    
        <a href="/admin/eventos" class="dashboard__enlace <?php echo pagina_actual('/eventos') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-calendar dashboard__icono"></i>
            <span class="dashboard__menu-texto">Eventos</span>
        </a>    
        <a href="/admin/registrados" class="dashboard__enlace <?php echo pagina_actual('/registrados') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-users dashboard__icono"></i>
            <span class="dashboard__menu-texto">Registrados</span>
        </a>    
        <a href="/admin/regalos" class="dashboard__enlace <?php echo pagina_actual('/regalos') ? 'dashboard__enlace--actual' : ''; ?>">
            <i class="fa-solid fa-gift dashboard__icono"></i>
            <span class="dashboard__menu-texto">Regalos</span>
        </a>    
    </nav>
</aside>