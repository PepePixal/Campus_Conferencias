//el código js irá dentro de una función IIFE (Inmediately Invoked Function Expresión), 
//esta función se autoejecuta inmediatamente despues de ser definida.
//Se usa para evitar la contaminación del ámbito global y encapsular sus variables.
(function() {

    //selecciona elemento html con id #regalos-grafica
    const grafica = document.querySelector('#regalos-grafica');

    //falida si el elemento existe antes de generar la gráfica
    if (grafica) {

        //llama a la función
        obtenerDatos();

        async function obtenerDatos() {
            //define la url del endpoint para la consulta fetch
            const url = '/api/regalos';
            //consulta fetch api, que retorna una respuesta
            const respuesta = await fetch(url);
            //espera el resultado de la api en formato json 
            const resultado = await respuesta.json()

            
            //** Código de la librería https://www.chartjs.org/docs/latest/getting-started/ */

            //selecciona elemento html con id , donde mostrar la gráfica
            const ctx = document.getElementById('regalos-grafica');
        
            new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: resultado.map( regalo => regalo.nombre),
                        datasets: [{
                            label: '',
                            data: resultado.map( regalo => regalo.total),
                            //agregamos colores de fondo para las barras
                            backgroundColor: [
                                '#ea580c',
                                '#84cc16',
                                '#22d3ee',
                                '#a855f7',
                                '#ef4444',
                                '#14b8a6',
                                '#db2777',
                                '#e11d48',
                                '#7e22ce'
                            ],
                            borderColor: [
                                '#ea580c',
                                '#84cc16',
                                '#22d3ee',
                                '#a855f7',
                                '#ef4444',
                                '#14b8a6',
                                '#db2777',
                                '#e11d48',
                                '#7e22ce'
                            ],
        
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        //para que no se muestre el legend con el contenido de label
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
            });

        }

    }

})();