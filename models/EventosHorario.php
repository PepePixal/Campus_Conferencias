<?php

namespace Model;

class EventosHorario extends ActiveRecord {

    protected static $tabla = 'eventos';
    protected static $columnasDB = ['id', 'categoria_id', 'dia_id', 'hora_id'];

    public $id;
    public $categoria_id;
    public $dia_id;
    public $hora_id;

    //No requiere constructor, porque es un módelo solo para consulta a la DB
}