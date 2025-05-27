<?php

namespace Model;

//NO requiere constructor porque este modelo,
//solo se usará para consultas a DB

class Hora extends ActiveRecord {
    protected static $tabla = 'horas';
    protected static $columnasDB = ['id', 'hora'];

    public $id;
    public $hora;
}