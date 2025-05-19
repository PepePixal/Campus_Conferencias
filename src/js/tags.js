//el código js irá dentro de una función IIFE (Inmediately Invoked Function Expresión), 
//esta función se autoejecuta inmediatamente despues de ser definida.
//Se usa para evitar la contaminación del ámbito global y encapsular sus variables.
(function() {
    //selecciona el elemento con id #tags_input y la asigna a la var tagsInput
    const tagsInput = document.querySelector('#tags_input');

    //arreglo almacena las etiquetas tecleadas en el input, cuando se teclee ,
    let tags = [];

    //si el elemto tagsInput existe, se ejecuta el código
    if(tagsInput) {
        //Escuchar cambios en el input, cuando se teclee algo, ejecuta función
        tagsInput.addEventListener('keypress', guardarTag);

        //función que recibe el evento escuchado en e
        function guardarTag(e) {
            //si el código de la tecla que dispara el evento e, es 44
            //significa que la tecla es una coma ,
            if(e.keyCode === 44) {

                //validar si se teclean espacios en blanco o no se ha tecleado nada,
                //no seguir ejecutando el código
                if(e.target.value.trim() === '' || e.target.value < 1) {
                    return
                }

                //cuando se ha pulsado la coma, evita la acción por defecto del evento e
                // que es escribir la coma, para que la coma , no se muestre en el input
                e.preventDefault();

                //como se ha pulsado la tecla coma ,
                //se genera un nuevo arreglo que toma copia del arreglo tags ...tags,
                //agrega el valor de lo que se haya tecleado en el input (e.target.value)
                //eliminando los valores en blanco con .trim() y lo reasigna al arreglo tags
                tags = [...tags, e.target.value.trim()];
                
                //limpia el contenido del input tagsInput, tras pulsar la coma ,
                tagsInput.value = '';


                console.log(tags);
            }
        }

    }


})()
