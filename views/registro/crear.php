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


            <!-- PAYPAL botones apilados Versión Curso-->
            <!-- requiere los scripts del final -->
            <div id="smart-button-container">
                <div style="text-align: center;">
                    <div id="paypal-button-container"></div>
                </div>
            </div>


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

            <!-- PayPal Botón único de pago versión web PayPal - código único -->
            <style>.pp-VGMWQ5PTXFMPG{text-align:center;border:none;border-radius:0.25rem;min-width:15rem;padding:0 2rem;height:3.125rem;font-weight:bold;background-color:#007df4;color:#FFFFFF;font-family:"Helvetica Neue",Arial,sans-serif;font-size:1.125rem;line-height:1.5rem;cursor:pointer;}</style>
            <form action="https://www.sandbox.paypal.com/ncp/payment/VGMWQ5PTXFMPG" method="post" target="_blank" style="display:inline-grid;justify-items:center;align-content:start;gap:0.5rem;">
                <input class="pp-VGMWQ5PTXFMPG" type="submit" value="Pagar ahora" />
                <img src=https://www.paypalobjects.com/images/Debit_Credit.svg alt="cards" />
                <section style="font-size: 0.75rem;"> Tecnología de <img src="https://www.paypalobjects.com/paypal-ui/logos/svg/paypal-wordmark-color.svg" alt="paypal" style="height:0.875rem;vertical-align:middle;"/></section>
            </form>
            <!-- --- fin PayPal -->

        </div>
    </div>
</main>


<!-- Paypal modelo Botones Apilados - TEST -->
<!-- Reemplazar CLIENT_ID por tu client id proporcionado al crear la app desde el developer dashboard) -->
<script src="https://www.paypal.com/sdk/js?client-id=AffZ5RgtNH8TXe8Gbn_9XMgYNwqc09wYEBWaaGk6FbGpp8g-4b3nA0-xq7vU61sKXqDWGnEgjuQaVv0P&enable-funding=venmo&currency=EUR" data-sdk-integration-source="button-factory"></script>
 
<script>
    function initPayPalButton() {
      paypal.Buttons({
        style: {
          shape: 'rect',
          color: 'blue',
          layout: 'vertical',
          label: 'pay',
        },
 
        createOrder: function(data, actions) {
          return actions.order.create({
            //el 1 de la descripción, será el id correspondiente a Presencial en la tabla paquetes
            purchase_units: [{
                "description": "1",
                "amount":{
                    "currency_code": "EUR",
                    "value":10
                }
            }]
          })
        },
 
        onApprove: function(data, actions) {
          return actions.order.capture().then(function(orderData) {
 
            //**Nuestro código agregado */
            //Una vez aprobado el pago por PayPal y recibida la info en orderData,
            //genera un nuevo objeto, con paquete_id, pago_id, token y usuario_id,
            // y lo almacena en la tabla registros de la DB

            //instancia nuevo objeto clave valor, de datos para envío por formulario
            const datos = new FormData();
            //agrega al objeto datos, la clave 'paquete_id' y el valor obtenido del objeto orderData
            datos.append('paquete_id', orderData.purchase_units[0].description);
            datos.append('pago_id', orderData.purchase_units[0].payments.captures[0].id);

            //solicitud fetch con método POST al endpoint, enviando datos del objeto tipo FormData
            fetch('/finalizar-registro/pagar', {
                method: 'POST',
                body: datos
            })
            .then( respuesta => respuesta.json())
            //recibe el objeto json resultado, del metodo pagar() de RegistroController()
            .then( resultado => {
                //si existe resultado en la propiedad resultado del objeto resultado:
                if(resultado.resultado) {
                    //redirecciona con las actions de PayPal, recibidas en onAprove: function
                    actions.redirect('http://localhost:3000/finalizar-registro/conferencias');
                }
            })

            //**Código original de PayPal */
            // Muestra los detalles del pago en la consola
                // console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
             // Muestra mensaje de exito de la operación
                // const element = document.getElementById('paypal-button-container');
                // element.innerHTML = '';
                // element.innerHTML = '<h3>Gracias por tu compra!</h3>';
             // Or go to another URL:  actions.redirect('thank_you.html');
            
          });
        },
 
        onError: function(err) {
          console.log(err);
        }
      }).render('#paypal-button-container');
    }
 
  initPayPalButton();
</script>
