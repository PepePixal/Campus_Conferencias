//el código js irá dentro de una función IIFE (Inmediately Invoked Function Expresión), 
//esta función se autoejecuta inmediatamente despues de ser definida.
//Se usa para evitar la contaminación del ámbito global y encapsular sus variables.
(function() {
    //seleciona elemento html del form con id = horas
    const horas = document.querySelector('#horas');

    //si existe el elemento horas, ejecuta el código
    if(horas) {

        //objeto para almacenar temporalmente, la categoria y el dia seleccionados en el form
        let busqueda = {
            categoria_id: '',
            dia: ''
        }

        //selecciona el elemento html cuyo atributo name="categoria_id",
        //el select de categoria del fomrulario
        const categoria = document.querySelector('[name="categoria_id"]');
        //selecciona todos elementos html cuyo atributo name="dia",
        //son todas las opciones del input type radio, obtenidas de la var $dias,
        //que contiene los elementos con id 1 Viernes y 2 Sábado, de la tabla dias, de la DB
        const dias = document.querySelectorAll('[name="dia"]');
        //selecciona elemento html cuyo atributo name="dia_id". El input oculto.
        const inputHiddenDia = document.querySelector('[name="dia_id"]');
        
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

            //La API requiere como argumentos, dia_id y categoria_id, que obtendremos del objeto busqueda. 
            //Validar que no están vacios, antes de llamar a la funcion que consulta a la API.
            //Si alguno de los values (atributos) del Objeto busqueda contiene '' strinc vacio, para el código
            if(Object.values(busqueda).includes('')) {
                return;
            }

            //llama a la función que consulta a la API
            buscarEventos();
        }

        //función que consulta a la API APIEventos.php, a través del endpoint /api/eventos-horario
        async function buscarEventos() {

            //deconstrucción del objeto busqueda, en dos variables con sus valores
            const { dia, categoria_id} = busqueda
            //define url para la consulta, incluyendo query string ? con los parametros para la API, 
            const url = `/api/eventos-horario?dia_id=${dia}&categoria_id=${categoria_id}`;
           
            //consulta fetch a la ulr de la API
            const resultado = await fetch(url);
            //obtiene eventos como resultado json
            const eventos = await resultado.json();

            obtenerHorasDisponibles()
        }

        function obtenerHorasDisponibles() {
            
        }
    }
})();