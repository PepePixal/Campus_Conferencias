<?php

namespace Classes;

class Paginacion {
    //definición de variables
    public $pagina_actual;
    public $registros_por_pagina;
    public $total_registros;

    //constructor con argumentos a recibir, inicializados por defecto
    public function __construct($pagina_actual = 1, $registros_por_pagina = 10, $total_registros = 0 ) {
        //(int) castea el valor recibido, lo convierte a entero
        $this->pagina_actual = (int) $pagina_actual;
        $this->registros_por_pagina = (int) $registros_por_pagina;
        $this->total_registros = (int) $total_registros;
    }

    //metodo que obtiene el offset de la paginación,
    //hasta que registro prescindir en cada página, segun la cantidad de registros por página.
    //Si se muestran 10 registros por pagina, en la 1ª prescindir de 0, en la 2ª de los 10 priemeros,
    //en la 3ª de los 20 primeros, etc. El número se usará en el OFFSET del query para la consulta SQL 
    public function offset() {
        return $this->registros_por_pagina * ($this->pagina_actual - 1);
    }

    //método que obtiene el total de páginas necesarias,
    //para mostrar todos los registros, según la cantidad de registros por página.
    //ceil() redondea al alaza un número, (el resultado de la división)
    public function total_paginas() {
        return ceil($this->total_registros / $this->registros_por_pagina);
    }

    //método que obtiene el número de página anterior a la que estamos,
    //si el número de página > 0, retorna el número, de lo contrario : retorna false
    public function pagina_anterior() {
        $anterior = $this->pagina_actual -1;
        return ($anterior > 0) ? $anterior : false;
    }

    //método que obtiene el número de página siguiente a la que estamos,
    //si el número de página < ó = que el total de páginas, retorna el número, si no : retorna false
    public function pagina_siguiente() {
        $siguiente = $this->pagina_actual +1;
        return ($siguiente <= $this->total_paginas()) ? $siguiente : false;
    }

    //método que retorna enlace html a la pagina siguiente, con query-string ?page= ,en la url
    public function enlace_siguiente() {
        //define var $html inicializada string vacio
        $html = '';
        //si el método pagina_siguiente() retorna un número de página siguiente y no un false:
        if($this->pagina_siguiente()) {
            //concatena código html a la var $html, para retornarlo e inyectarlo en otro html
            //La barra \ escapa las comillas para que no tengan efecto dentro del string " " principal
            $html .= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"?page={$this->pagina_siguiente()}\"> Siguiente &raquo</a>";
        }
        return $html;
    }

    //método que retorna enlace html a la pagina anterior, con query-string ?page= ,en la url
    public function enlace_anterior() {
        //define var $html inicializada string vacio
        $html = '';
        //si el método pagina_anterior() retorna un número de página anterior y no un false
        if($this->pagina_anterior()) {
            //concatena código html a la var $html, para retornarlo e inyectarlo en otro html
            //La barra \ escapa las comillas para que no tengan efecto dentro del string " " principal
            $html .= "<a class=\"paginacion__enlace paginacion__enlace--texto\" href=\"?page={$this->pagina_anterior()}\">&laquo Anterior </a>";
        }
        return $html;
    }

    //genera números de páginas para paginación
    public function numeros_paginas() {
        //define varl $html con string vacio
        $html = '';
        //se ejecuta tantas veces como número total de páginas necesarias
        for( $i = 1; $i <= $this->total_paginas(); $i++ ) {
            //si el índice $i es igual a la página que nos encontramos:
            if($i === $this->pagina_actual) {
                //mostrar un span al número de página, en lugar de un enlace
                $html .= "<span class=\"paginacion__enlace paginacion__enlace--actual \">$i</span>";
            
            //si el índeic $i no es igual a la página que nos encontramos
            } else {
                //html con enlace a la página 
                $html .= "<a class=\"paginacion__enlace paginacion__enlace--numero \" href=\"?page=$i\">$i</a>";
            }
        } 

        return $html;
    }

    //método que retorna código html con un div y los enlaces de pagina_anterior() y pagina_siguiente()
    public function paginacion() {
        //define varl $html con string vacio
        $html = '';
        //si el total_registros es > 1 requerimos paginación para mostrarlos
        if($this->total_registros > 1) {
            //concatena código html a la var $html
            $html .= '<div class="paginacion">';
            $html .= $this->enlace_anterior();
            $html .= $this->numeros_paginas();
            $html .= $this->enlace_siguiente();
            $html .= '</div>';
        }

        return $html;
    }
}