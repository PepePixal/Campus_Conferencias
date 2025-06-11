<?php

namespace Model;

class Paquete extends ActiveRecord {
    protected static $tabla = 'paquetes';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

   // No requiere constructor, porque es solo para consultas

}