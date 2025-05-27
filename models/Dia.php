<?php

namespace Model;

//NO requiere constructor porque este modelo,
//solo se usará para consultas a DB

class Dia extends ActiveRecord {
    protected static $tabla = 'dias';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;
}