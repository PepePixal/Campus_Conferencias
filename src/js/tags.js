//el código js irá dentro de una función IIFE (Inmediately Invoked Function Expresión), 
//esta función se autoejecuta inmediatamente despues de ser definida.
//Se usa para evitar la contaminación del ámbito global y encapsular sus variables.
(function() {
    //selecciona el elemento con id #tags_input y la asigna a la var tagsInput
    const tagsInput = document.querySelector('#tags_input');
    
    //si el elemento tagsInput existe, se ejecuta el código
    if(tagsInput) {
        
        //arreglo almacena las etiquetas tecleadas en el input, cuando se teclee ,
        let tags = [];

        //selecciona elemento html con id #tags, donde se mostraran los tags
        const tagsDiv = document.querySelector('#tags');

        //selecciona elemento html del form, con el atributo name="tags"
        //es el input hidden (oculto) del formulario agregar ponentes
        const tagsInputHidden = document.querySelector('[name="tags"]');

        //**Recuperar los tags del value del input hidden (oculto)
        //si el atributo value de elemento tagsInputHidden, contiene info
        if(tagsInputHidden.value !== '') {
            //extrae los valores separados por ',' del string, 
            //los convierte en arreglo y los asigna a tags
            tags = tagsInputHidden.value.split(',');
            //llama metodo que muestra los tags en el formulario
            mostrarTags()
        }

        //Escuchar cambios en el input, cuando se teclee algo, ejecuta función
        tagsInput.addEventListener('keypress', guardarTag);

        //función que recibe el evento escuchado en e
        function guardarTag(e) {
            //si el código de la tecla que dispara el evento e, es 44
            //significa que la tecla es una coma ,
            if(e.keyCode === 44) {

                //validar si se teclean espacios en blanco, o no se ha tecleado nada,
                //no seguir ejecutando el código
                if(e.target.value.trim() === '' || e.target.value < 1) {
                    return
                }

                //cuando se ha pulsado la coma, evita la acción por defecto del evento e
                // que es escribir la coma, para que la coma , no se muestre en el input
                e.preventDefault();

                //como se ha pulsado la tecla coma ,
                //se genera un nuevo arreglo que toma copia del arreglo tags ...tags,
                //agrega el valor de lo que se haya tecleado en el input (e.target.value),
                //eliminando los valores en blanco con .trim() y lo reasigna al arreglo tags
                tags = [...tags, e.target.value.trim()];
                
                //limpia el contenido del input tagsInput, tras pulsar la coma ,
                tagsInput.value = '';

                //llama función mostra los tags
                mostrarTags();
            }
        }

        //Muestra cada tag en el formulario
        function mostrarTags() {
            //agrega al div un string vacio, para limpiar
            tagsDiv.textContent = '';

            //itera el arreglo tags con las etiquetas introducidas
            //y por cada etiqueta en tag
            tags.forEach( tag => {
                //crea un elemento LI y lo asigna a etiqueta
                const etiqueta = document.createElement('LI');
                //agrega la clase formulario__tag al elemento LI
                etiqueta.classList.add('formulario__tag');
                //agrega el valor (string) de la etiqueta iterada, en tag, al LI etiqueta
                etiqueta.textContent = tag;
                //asigna evento dobleclick a la etiqueta, que ejecutará la función eliminarTag
                etiqueta.ondblclick = eliminarTag
                //inserta el LI etiqueta, como hijo del div con id #tags
                tagsDiv.appendChild(etiqueta); 
            })

            //llama la función que asigna, el tag mostrado, al input hidden (oculto)
            actualizarInputHidden()
        }

        //elimina del DOM y del arreglo tags, el tag recibido como evento dobleclick en e,
        function eliminarTag(e) {
            //elimina del DOM, el elemento (tag) que origna el evento e, el e.target
            e.target.remove()
            //filtra y obtiene cada tag sobre el que no se haya aplicado el e.target,
            //los no eliminados y los reasigna al arreglo tags
            tags = tags.filter(tag => tag !== e.target.textContent)
            //llama método que asigna los tags al value del input hidden, para la DB
            actualizarInputHidden()
        } 

        //función que asigna cada tag, al value de input hidden (oculto)
        //para actualizarlos en la DB
        function actualizarInputHidden() {
            //asigna al value del input hidden, el arreglo tag convertido a string
            tagsInputHidden.value = tags.toString();
        }
    }

})()
