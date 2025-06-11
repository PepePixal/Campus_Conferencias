<main class="registro">
    <h2 class="registro__heading"><?php echo $titulo; ?></h2>
    <p class="registro__descripcion">Elige tu plan de asistente</p>

    <div class="precios__grid">
        <div class="precio">
            <h3 class="precio__nombre">Pase Gratis</h3>
            <ul class="precio__lista">
                <li class="precio__elemento">Acceso Virtual a CampusDevWeb</li>
            </ul>
            <p class="precio__precio">0 €</p>

            <form method="POST" action="/finalizar-registro/gratis">
                <input type="submit" class="precios__submit" value="Incripción Gratuita">
            </form>
        </div>
        
        <div class="precio">
            <h3 class="precio__nombre">Pase Presencial</h3>
            <ul class="precio__lista">
                <li class="precio__elemento">Acceso Virtual a CampusDevWeb</li>
                <li class="precio__elemento">Pase para 2 días</li>
                <li class="precio__elemento">Acceso a Talleres y Conferencias</li>
                <li class="precio__elemento">Acceso a las Grabaciones</li>
                <li class="precio__elemento">Camiseta del Evento</li>
                <li class="precio__elemento">Comida y Bebida</li>
            </ul>
            <p class="precio__precio">199 €</p>

        </div>

        <div class="precio">
            <h3 class="precio__nombre">Pase Virtual</h3>
            <ul class="precio__lista">
                <li class="precio__elemento">Acceso Virtual a CampusDevWeb</li>
                <li class="precio__elemento">Pase para 2 días</li>
                <li class="precio__elemento">Enlace a Talleres y Conferencias</li>
                <li class="precio__elemento">Acceso a las Grabaciones</li>
            </ul>
            <p class="precio__precio">49 €</p>
        </div>
    </div>


</main>
