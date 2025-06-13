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

            <div id="paypal-container-VGMWQ5PTXFMPG"></div>
            <script>
                paypal.HostedButtons({
                    hostedButtonId: "VGMWQ5PTXFMPG",
                }).render("#paypal-container-VGMWQ5PTXFMPG")
            </script>

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

            <style>.pp-VGMWQ5PTXFMPG{text-align:center;border:none;border-radius:0.25rem;min-width:15rem;padding:0 2rem;height:3.125rem;font-weight:bold;background-color:#007df4;color:#FFFFFF;font-family:"Helvetica Neue",Arial,sans-serif;font-size:1.125rem;line-height:1.5rem;cursor:pointer;}</style>
            <form action="https://www.sandbox.paypal.com/ncp/payment/VGMWQ5PTXFMPG" method="post" target="_blank" style="display:inline-grid;justify-items:center;align-content:start;gap:0.5rem;">
                <input class="pp-VGMWQ5PTXFMPG" type="submit" value="Pagar ahora" />
                <img src=https://www.paypalobjects.com/images/Debit_Credit.svg alt="cards" />
                <section style="font-size: 0.75rem;"> Tecnología de <img src="https://www.paypalobjects.com/paypal-ui/logos/svg/paypal-wordmark-color.svg" alt="paypal" style="height:0.875rem;vertical-align:middle;"/></section>
            </form>

        </div>
    </div>

</main>
