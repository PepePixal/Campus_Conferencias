//el código js irá dentro de una función IIFE (Inmediately Invoked Function Expresión), 
//esta función se autoejecuta inmediatamente despues de ser definida.
//Se usa para evitar la contaminación del ámbito global y encapsular sus variables.
(function() {
    //seleciona elemento html del form con id = horas
    const horas = document.querySelector('#horas');

    //si existe el elemento horas, ejecuta el código
    if(horas) {

        //selecciona el elemento html cuyo atributo name="categoria_id",
        //el select de categoria del fomrulario
        const categoria = document.querySelector('[name="categoria_id"]');
        //selecciona todos elementos html cuyo atributo name="dia",
        //son todas las opciones del input type radio, obtenidas de la var $dias,
        //que contiene los elementos con id 1 Viernes y 2 Sábado, de la tabla dias, de la DB
        const dias = document.querySelectorAll('[name="dia"]');

        //selecciona elemento html cuyo atributo name="dia_id". El input oculto.
        const inputHiddenDia = document.querySelector('[name="dia_id"]');
        //selecciona elemento html cuyo atributo name="hora_id". El input oculto.
        const inputHiddenHora = document.querySelector('[name="hora_id"]');

        //objeto para almacenar temporalmente, la categoria y el dia seleccionados en el form
        let busqueda = {
            //asigna a categoria_id, el valor (numérico +) de su value, si ya lo tiene, si no ||, no asigna nada ''
            categoria_id: +categoria.value || '',
            //asigna a dia, el valor (numérico +) del value del input, si ya lo tiene, si no ||, no asigna nada ''
            dia: +inputHiddenDia.value || ''
        }
        
        //validar, que las propiedades del objeto busqueda contiene info,
        //Si de los values de las propiedades del Objeto busqueda, ! NO contiene '' string vacio,
        //significa que tienen información, esto ocurre cuando los estamos editanto, no cuando se crean
        if(!Object.values(busqueda).includes('')) {

            //agrega una función anónima asyncrona dentro de una función IIFE,
            //para esperar que se ejecute la funci´n buscarEventos()
            (async () => {

                //llama método que busca eventos según la categoria_id y el dia,
                //y a su vez obtiene las horas disponibles y ocupadas
                await buscarEventos();

                //obtiene el value del inputHiddenHora y lo asigna a id
                const id = inputHiddenHora.value;
                
                //** Resaltar la hora seleccionada para el evento con esta categoria_id y dia,
                //** el evento que estamos editando.
                //obtiene el elemento html cuyo atributo personalizado data-hora-id = al value del inputHiddenHora, en id
                const horaSeleccionada = document.querySelector(`[data-hora-id="${id}"]`);

                
                //elimina la clase horas__hora--deshabilitada a la hora del elemento que estamos editando
                horaSeleccionada.classList.remove('horas__hora--deshabilitada');
                
                horaSeleccionada.classList.add('horas__hora--seleccionada');


            })();
 
        }



        //asigna escuchador de evento change y función, al select de categoría del formulario
        categoria.addEventListener('change', terminoBusqueda);
        
        //itera los dias, para poder asignar un escuchador de evento (change) y una función,
        //a cada elemento dia de dias.
        dias.forEach(dia => dia.addEventListener('change', terminoBusqueda));

        //función terminoBusqueda que recibe eventos en (e),
        //tanto de categoria como de dia
        function terminoBusqueda(e){
            //asigna, a la propiedad del objeto busqueda, cuya clave es igual al valor del atributo .name,
            //del elemento e.target que origina el evento e,  
            //le asigna el valor del atributo .value, del elemento e.target que origina el evento e. 
            //Resumiendo, a la propiedad categoria_id del objeto busqueda, le asigna ó 1 ó 2 (ó Conferenica ó Workshop), 
            //y a la propiedad dia del objeto busqueda, le asigna ó 1 ó 2 (ó Viernes ó Sabado),
            busqueda[e.target.name] = e.target.value;

            //**Una vez almacenados en busqueda, la categoría y o el día, seleccionados, y antes buscar eventos:
            //Reiniciar el value de los campos input ocultos, del form: 
            inputHiddenHora.value = '';
            inputHiddenDia.value = '';
            //Reinicia la clase de las horas seleccionadas, del form:
            //selecciona el elemento (li) con clase .horas__hora--seleccionada
            const horaPrevia = document.querySelector('.horas__hora--seleccionada');
            //si existe algún elemento con esa clase:
            if(horaPrevia) {
                //elimina la clase del elemento
                horaPrevia.classList.remove('horas__hora--seleccionada');
            }

            //La API requiere como argumentos, dia_id y categoria_id, que obtendremos del objeto busqueda. 
            //Validar que no están vacios, antes de llamar a la funcion que consulta a la API.
            //Si alguno de los values de las propiedades del Objeto busqueda, contiene '' string vacio, para el código
            if(Object.values(busqueda).includes('')) {
                return;
            }

            //llama a la función que consulta a la API
            buscarEventos();
        }

        //función que consulta a la API APIEventos.php, para obtener eventos según la categoría y el día
        async function buscarEventos() {

            //deconstrucción de las propiedades de objeto busqueda, a dos variables con sus valores
            const { dia, categoria_id} = busqueda
            //define url para la consulta, incluyendo query string ? con los parametros para la API, 
            const url = `/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`;
           
            //consulta fetch a la ulr de la API
            const resultado = await fetch(url);
            //obtiene los eventos como objetos json, del resultado de la consulta y
            // los retorna como arreglo a la var eventos
            const eventos = await resultado.json();

            //llama metodo que obtiene las horas disponibles,
            // enviando los eventos ya registrados, como argumento
            obtenerHorasDisponibles(eventos);
        }

        //recibe los eventos ya registrados como parámetro,
        //para obtener los li con las horas disponibles
        function obtenerHorasDisponibles(eventos) {

            //**Antes de obtener las horas disponibles, deshabilita todas las horas.
            //selecciona todos los li, del elemento padre con id horas,
            const listadoHoras = document.querySelectorAll('#horas li');
            //itera el listado con todas las horas y les agrega la clase horas__hora--deshabilitada
            listadoHoras.forEach(li => li.classList.add('horas__hora--deshabilitada'));

            //**Comprobar los eventos ya registrados,
            //mapea los eventos y genera un nuevo arreglo de horas, con la hora en hora_id, de cada evento,
            //asigna el nuevo arreglo a horasTomadas
            const horasTomadas = eventos.map( evento => evento.hora_id);

            //listadoHoras es de tipo NodeList porque viene del querySelector,
            //convertir el NodeList a arreglo con los li, para poder filtrarlo posteriormente
            const listadoHorasArray = Array.from(listadoHoras);

            //compara los li de listadoHorasArray con los li de horasTomadas, por sus atributos dataset.horaId,
            //filtra los que NO ! coincidan, obteniendo un nuevo arreglo resultado con los li de la horas no tomadas
            const resultado = listadoHorasArray.filter( li => !horasTomadas.includes(li.dataset.horaId))

            //itera resultado con los li de las horas no reservadas y les quita la clase horas__hora--deshabilidada
            resultado.forEach( li => li.classList.remove('horas__hora--deshabilitada'));

            //selecciona, del elemento padre ul con id horas, 
            //, todos los elementos li, menos (:not) los que tienen la clase .horas__hora--deshabilitada 
            const horasDisponibles = document.querySelectorAll('#horas li:not(.horas__hora--deshabilitada)');
            //itera horasDisponibles para asignarle un evento click y función, a cada elemento li (cada hora)
            horasDisponibles.forEach(hora => hora.addEventListener('click', seleccionarHora))   
        }

        //se ejecuta con el evento (e) click, de cada hora del formulario
        function seleccionarHora(e) {
            //**deshabilitar la hora previa seleccionada, si hay nueva selección de hora
            //asigna a horaPrevia, el elemento html cuya clase tenga '.horas__hora--selccionada'
            const horaPrevia = document.querySelector('.horas__hora--seleccionada');
            //si existe un elemento en horaPrevia:
            if(horaPrevia) {
                //elimina la clase del elemento (li)
                horaPrevia.classList.remove('horas__hora--seleccionada')
            }

            //agrega la clase 'horas__hora--seleccionada,
            //al elemento html (li) que dispara el evento (e) al click.
            //clase agregada para dar estilos css
            e.target.classList.add('horas__hora--seleccionada');

            //al atributo value, del input oculto con name="hora_id",
            //(que hemos asisnado a la var inputHiddenHora),
            //le agrega el valor del atributo personalizado (dataset) data-hora-id,
            //del elemento li, que origina el evento (e), con click     
            inputHiddenHora.value = e.target.dataset.horaId;

            //asignar el value del dia seleccionado (checked), al value del input dia, oculto
            inputHiddenDia.value = document.querySelector('[name="dia"]:checked').value 
        }

    }
})();