<?php

namespace Model;

//NO requiere constructor porque este modelo,
//solo se usará para consultas a DB

class Categoria extends ActiveRecord {
    protected static $tabla = 'categorias';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;
}