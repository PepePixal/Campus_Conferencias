//importa la librería js instalada sweetalert2, para las alertas
import Swal from 'sweetalert2';

//el código js irá dentro de una función IIFE (Inmediately Invoked Function Expresión), 
//esta función se autoejecuta inmediatamente despues de ser definida.
//Se usa para evitar la contaminación del ámbito global y encapsular sus variables.
(function() {
    //define arreglo para los eventos que se vayan seleccionando
    let eventos = [];

    //selecciona el elemento div donde se insertaran los registros seleccioandos
    const resumen = document.querySelector('#registro-resumen');

    //si resumen contiene el elemento, ejecuta el siguiente código
    if(resumen) {

        //selecciona Todos los elementos (botones), con clase .evento__agregar
        const eventosBoton = document.querySelectorAll('.evento__agregar');
        //como es un querySelector a All, para asignarles eventos a cada uno, se tienen que iterar y
        //asignar a cada boton el evento y la función
        eventosBoton.forEach(boton => boton.addEventListener('click', selecionarEvento));

        //selecciona el form por su id registro, en conferencias.php
        const formularioRegistro = document.querySelector('#registro');
        //agrega escucha del evento submit del input del form y función
        formularioRegistro.addEventListener('submit', submitFormulario);

        //llama mostrarEventos para que se muestre inicialmente el mensaje 'No hay Eventos....
        mostrarEventos();

        function selecionarEvento(e) {

            //valida la cantidad de eventos seleccionados, para restringir a max 5
            if(eventos.length < 5) {
                //Deshabilita el elemento que genera el evento click, para que no se pueda volfer a clickar
                e.target.disabled = true;
        
                //crea una copia del arreglo ...eventos y le agrega un objeto con llaves y valores, nuevos
                eventos = [...eventos, {
                    //obtiene el valor de dataset.id, del elemento que dispara el evento e y lo asigna a id:
                    id: e.target.dataset.id,
                    //se posiciona en el elemento padre del elemento que origina el evento e, (div .evento-informacion),
                    //selecciona el elemento hijo cuya clase es .evento__nombre (h4 .evento__nombre),
                    //obtiene el texto contenido (en el h4) eliminando los espacios y lo asigna a titulo
                    titulo: e.target.parentElement.querySelector('.evento__nombre').textContent.trim()
                }];
        
                //función para mostrar  en el aside registros, los eventos agregados,
                mostrarEventos();

            } else {
                //alerta usando la librería importada sweetalert2
                Swal.fire({
                    title: 'Error',
                    text: 'Máximo 5 Eventos',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
            }
        }

        //función para mostrar ,en el aside registros, los eventos agregados,
        function mostrarEventos() {
            // Limpiar los eventos del html destino, antes de insertar los nuevos
            limpiarEventos();

            //valida si el arreglo eventos tiene contenido
            if(eventos.length > 0) {
                //itera el arreglo eventos y por cada evento:
                eventos.forEach( evento => {
                    //crea un elemento html DIV
                    const eventoDOM = document.createElement('DIV');
                    //agrega class 'registro__evento' al DIV
                    eventoDOM.classList.add('registro__evento');
                    //crea elemento html H3
                    const titulo = document.createElement('H3');
                    //agrega class al titulo
                    titulo.classList.add('registro__nombre');
                    //agrega el avlor de evento.titulo al elementto h3 titulo
                    titulo.textContent = evento.titulo;

                    //crea botón eliminar
                    const botonEliminar = document.createElement('BUTTON');
                    //agrega class al button
                    botonEliminar.classList.add('registro__eliminar');
                    //innerHTML para insertar icono
                    botonEliminar.innerHTML = `<i class="fa-solid fa-trash"></i>`;
                    //evento click al boton
                    botonEliminar.onclick = function() {
                        //llama funcion enviando evento.id
                        eliminarEvento(evento.id);
                    }

                    //agregar h3 título como hijo del div eventoDOM
                    eventoDOM.appendChild(titulo);
                    //agregar boton eliminar
                    eventoDOM.appendChild(botonEliminar);
                    // Renderizar, insertar eventoDOM como hijo, en el elemento html 
                    // con id registro__resumen, del aside de views/registro/conferencias.php
                    resumen.appendChild(eventoDOM);
                })

            //como el arreglo eventos no tiene contenido
            } else {
                //genera un parrafo P
                const noRegistro = document.createElement('P');
                //agrega texto al párrafo
                noRegistro.textContent = 'No hay Eventos, agrega hasta un máximo de 5';
                //agrega class al párrafo
                noRegistro.classList.add('registro__texto');
                //agrega el parrafo noRegistro, como hijo del elemento resumen
                resumen.appendChild(noRegistro);
            }
        }

        function eliminarEvento(id) {
            //filtra eventos y obtiene cada evento cuyo .id no sea != al id del evento a eliminar
            eventos = eventos.filter( evento => evento.id !== id);

            //selecciona el botón agregar de la tarjeta, cuyo data-id es igual al id 
            //del evento eliminado, para volver a activar el botón
            const botonAgregar = document.querySelector(`[data-id="${id}"`);
            //habilita el botón Agregar de la tarjeta
            botonAgregar.disabled = false;
            
            //renderiza solo con los eventos resultantes del filtrado
            mostrarEventos();
        }

        //elimina los elementos de la vista
        function limpiarEventos() {
            //mientras el elemento html resumen tenga un primer elemento hijo:
            while(resumen.firstChild)
                //eliminar el elemento html hijo, del elemento resumen
                resumen.removeChild(resumen.firstChild);
        }

        //envía el form para el registro de eventos y regalo, recibe evento e
        async function submitFormulario(e) {
            //previene la acción por defecto del (e) submit form, que son el action y el method,
            //porque vamos a enviar los datos por fetch api
            e.preventDefault();
            
            //obtener el id del regalo seleccionado, 
            //obteniendo el value (id) del option, del select con ide #regalo, del form 
            const regaloId = document.querySelector('#regalo').value
            //obtener los id de los eventos seleccionados,
            //mapeando el arreglo eventos y por cada evento obtener su id
            const eventosId = eventos.map(evento => evento.id);

            //valida si no hay eventos o no hay regalo, seleccionados
            if( eventosId.length === 0 || regaloId === '') {
                //alerta usando la librería importada sweetalert2
                Swal.fire({
                    title: 'Error',
                    text: 'Elige al menos un Evento y un Regalo',
                    icon: 'error',
                    confirmButtonText: 'OK'
                })
                //para el código aquí
                return;
            }

            //genera un objeto tipo FormData Prototype, para el envio con fetch POST
            const datos = new FormData();
            //agrega los id de los eventos y el regalo, seleccionados, al arreglo datos
            datos.append('eventos', eventosId);
            datos.append('regalo_id', regaloId);

            //consulta fetch api con método POST, enviando datos
            const url = '/finalizar-registro/conferencias';
            //hace la consulta y obtiene una respuesta
            const respuesta = await fetch( url, {
                method: 'POST',
                body: datos
            });

            //espera a recibir respuesta de la solicitud tipo POST
            //en el método conferencias() de RegistroController.php
            const resultado = await respuesta.json();

            //valida si la llave .resultado del obj json resultado, es true
            if(resultado.resultado) {
                //alerta usando la librería importada sweetalert2
                Swal.fire(
                    'Registrado',
                    'Tus conferencias se han registrado correctamente, te esperamos en el CampusDevWeb',
                    'success'

                //cuando el usuario pulse el botón de la alerta, entonces se ejecuta función call back
                //que redirige a la url pasando el token en el id de la url, que mostrará el boleto virtual
                ).then( () => location.href = `/boleto?id=${resultado.token}`)


            } else {
                //alerta usando la librería importada sweetalert2
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error en el registro. Revisa la disponibilidad.',
                    icon: 'error',
                    confirmButtonText: 'OK'

                //cuando el usuario pulse el botón de la alerta, entonces se ejecuta función call back,
                //que recargará la página actual
                }).then( () => location.reload())
            }
            
        }

    }

})();
