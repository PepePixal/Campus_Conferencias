//el código js irá dentro de una función IIFE (Inmediately Invoked Function Expresión), 
//esta función se autoejecuta inmediatamente despues de ser definida.
//Se usa para evitar la contaminación del ámbito global y encapsular sus variables.
(function() {

    //seleciona elemento con id=ponentes
    const ponentesInput = document.querySelector('#ponentes');

    //valida si existen ponentes
    if(ponentesInput) {
        //array q almacenara temporalmente los ponetes consultados a la API
        let ponentes = [];
        //array a almacena temporalmente los ponentes filtrados
        let ponentesFiltrados = [];

        //seleciona elemento html cuya id es
        const listadoPonentes = document.querySelector('#listado-ponentes');
        //selecciona elemento html, el input oculto para el ponente
        const ponenteHidden = document.querySelector('[name="ponente_id"]');

        //llama a funcion
        obtenerPonentes();

        //asigna un listener de evento tipo input y una función, al input
        ponentesInput.addEventListener('input', buscarPonentes );

        //func que obtiene ponentes a través de la API
        async function obtenerPonentes() {
            
            //def url del endpoint de la API
            const url = `/api/ponentes`;
            //consulta con método fetch() a la API
            const respuesta = await fetch(url);
            //obtiene la info .json, del resultado de la consulta, en un arreglo de objetos json
            //obtiene todos los datos (columnas) de todos los clientes (registros)
            const resultado = await respuesta.json(); 
            
            //llama función enviando el arreglo de objetos json resulado con toda la info
            formatearPonentes(resultado);  
        }

        //obtiene un nuevo arreglo solo con la info necesaria, a partir del arrayPonentes recibido
        function formatearPonentes(arrayPonentes = []) {
            //mapea arrayPonentes y genera un nuevo array retornando solo con el id y nombre
            //de cada ponente, asignandolo a la var array ponentes
            ponentes = arrayPonentes.map( ponente => {
                return {
                    id: ponente.id,
                    //.trim() elimina los espacios en blanco al inicio y al final del string
                    nombre: `${ponente.nombre.trim()} ${ponente.apellido.trim()}`
                }
            })
        }

        // //función que recibe el evento input  
        // function buscarPonentes(e) {
        //     //asigna a busqueda, el value del elemento input (target), que dispara el evento e
        //     let busqueda = e.target.value;

        //     //si la longitud del valor de busqueda es > 3 
        //     //(si en el input ya se han escrito más de 3 carácteres)
        //     if(busqueda.length > 3) {
        //         //crea un aexpresión regular con el valor de la var busqueda,
        //         //la bandera "i" para que no tenga en cuenta si son máyusculas o minusculas
        //         const expresion = new RegExp(busqueda, "i");
        //         //genera un nuevor arrglo, filtrando el arreglo ponentes, y por cada ponente:
        //         ponentesFiltrados = ponentes.filter( ponente => {
        //             //busca en el nombre del ponente, convertido a minúsculas,
        //             //la expresión regular o patrón, que contiene la var expresion,
        //             // si lo contiene retorna 0, si no lo contiene retorna -1
        //             if( ponente.nombre.toLowerCase().search(expresion) != -1 ) {
        //                 //retorna el ponete o ponentes al arreglo ponentesFiltrados
        //                 return ponente
        //             }
        //         });
        //         console.log(ponentesFiltrados)
        //     }
        // }

        //función que recibe el evento input  
        function buscarPonentes(e) {

            //asigna a busqueda, el value del elemento input (target), que dispara el evento e
            const busqueda = e.target.value;
        
            //si la longitud del valor de busqueda es > 3 
            //(si en el input ya se han escrito más de 3 carácteres)
            if(busqueda.length > 3) {

                //RegExp() crea un expresión regular con lo que contenga la var busqueda,
                //normalize() convierte una cadena de caracteres en una forma normalizada,
                //lo que significa que los caracteres con acentos son reemplazados con sus versiones sin acentos,
                //la bandera "i" hace insensible a mayúsculas o minúsculas.
                //TODO ESTO NOS PERMITIRÁ HACER UNA BÚSQUEDA SIN TENER EN CUENTA LOS ACENTOS Y LAS MAYUSCULAS
                const expresion = new RegExp(busqueda.normalize('NFD').replace(/[\u0300-\u036f]/g, ""), "i");

                //genera un nuevor arrglo, filtrando el arreglo ponentes, y por cada ponente:
                ponentesFiltrados = ponentes.filter(ponente => {

                    //busca la expresión regular o patrón, que contiene la var expresion,
                    //en el nombre del ponente, normalizado y convertido a minúsculas,
                    // si contiene la expresión retorna 0, si no la contiene retorna -1
                    if(ponente.nombre.normalize('NFD').replace(/[\u0300-\u036f]/g, "").toLowerCase().search(expresion) != -1) {
                        //como contiene la expresión, retorna ponente a ponentes filtrados
                        return ponente
                    }
                })

            } else {
                ponentesFiltrados = []
            }
        
            //llama función, una vez filtrados los ponentes buscados
            mostrarPonentes();
        }

        function mostrarPonentes() {

            //Limpiar el listado de ponentes.
            //mientras listado ponentes tenga un elemento hijo
            while(listadoPonentes.firstChild) {
                //eliminar el elemento hijo
                listadoPonentes.removeChild(listadoPonentes.firstChild);
            }

            //si ponentesFiltrados contiene algo:
            if(ponentesFiltrados.length > 0) {

                //itera los ponentes filtrados encotrados y por cada ponente:
                ponentesFiltrados.forEach( ponente => {
                    //crea elemento html li y lo asigna a ponentHTML
                    const ponenteHTML = document.createElement('LI');
                    //agrega class al elemento li
                    ponenteHTML.classList.add('listado-ponentes__ponente');
                    //agrega al contenido del li, el nombre del ponente filtrado
                    ponenteHTML.textContent = ponente.nombre;
                    //agrega el id del poenente, al atributo dataset ponenteId al li
                    ponenteHTML.dataset.ponenteId = ponente.id
                    //agrega evento onclick al ponente y metodo a ejecutar
                    ponenteHTML.onclick =  seleccionarPonente;

                    //Agregar el o los li al DOM
                    listadoPonentes.appendChild(ponenteHTML);
                })

            //si ponentesFiltrados NO contiene nada
            } else {
                //crea elemento html p
                const noResultados = document.createElement('P');
                noResultados.classList.add('listado-ponentes__no-resultado');
                noResultados.textContent = 'No Hay Resultados en tu búsquda';

                //Agregar el P párrafo al DOM
                listadoPonentes.appendChild(noResultados);
             }
        }

        function seleccionarPonente(e) {
            //selecciona el elemento html que dispara el evento
            const ponente = e.target

            //**eliminar la clase --seleccionado, por si ya la tiene asignada
            //selecciona elemento html con clase = , puede que ya exista o no
            const ponentePrevio = document.querySelector('.listado-ponentes__ponente--seleccionado');
            //si ya existe un elemento con esa clase, se la elimina
            if(ponentePrevio) {
                ponentePrevio.classList.remove('listado-ponentes__ponente--seleccionado');
            }

            //agrega clase al elemento html
            ponente.classList.add('listado-ponentes__ponente--seleccionado');

            //agrega el valor del atributo dataset ponenteId del ponente seleccionado, 
            // al atributo value del input poneneteHidden
            ponenteHidden.value = ponente.dataset.ponenteId

        }
    }

})();