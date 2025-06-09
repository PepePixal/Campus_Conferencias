<?php
namespace Model;
class ActiveRecord {

    // Base DE DATOS
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];
    
    // Definir la conexión a la BD - includes/database.php
    public static function setDB($database) {
        self::$db = $database;
    }

    // Setear un tipo de Alerta
    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Obtener las alertas
    public static function getAlertas() {
        return static::$alertas;
    }

    // Validación que se hereda en modelos
    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para crear un objeto en Memoria (Active Record)
    public static function consultarSQL($query) {
        // Consultar la base de datos
        $resultado = self::$db->query($query);

        // Iterar los resultados
        $array = [];
        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        // liberar la memoria
        $resultado->free();

        // retornar los resultados
        return $array;
    }

    // Crea el objeto en memoria que es igual al de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;

        foreach($registro as $key => $value ) {
            if(property_exists( $objeto, $key  )) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos antes de guardarlos en la BD
    public function sanitizarAtributos() {
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value ) {
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // Sincroniza BD con Objetos en memoria
    public function sincronizar($args=[]) { 
        foreach($args as $key => $value) {
          if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
          }
        }
    }

    // Registros - CRUD
    public function guardar() {
        $resultado = '';
        if(!is_null($this->id)) {
            // actualizar
            $resultado = $this->actualizar();
        } else {
            // Creando un nuevo registro
            $resultado = $this->crear();
        }
        return $resultado;
    }

    // Obtener todos los Registros, recibe como parámetro el orden que queramos,
    // si no recibe nada el orden por defecto es DESC
    public static function all($orden = 'DESC') {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id $orden";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = $id";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " LIMIT $limite ORDER BY id DESC" ;
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE $columna = '$valor'";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Retorna los registros oredenados según la $columna y en un $orden
    public static function ordenar($columna, $orden) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY $columna $orden";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    
    // Busqueda Where según parámetros recibidos como arreglo
    public static function whereArray($array = []) {
        //define primera parte del $query SQL
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
        //itera el arreglo recibido, obteniendo llave $key y valor $value, de cada elemento
        foreach($array as $key => $value){
            //si la llave $key que está iterando, ES LA ÚLTIMA del arreglo
            if($key == array_key_last($array)) {
                //concatena a la parte del $query ya definida, sin el AND:
                //el nombre de la llave y su valor, de cada elemento recibido en $array
                $query .= " $key = '$value'";
            
            ///si la llave $key que está iterando, todavía NO es la última del arreglo:
            } else {
                //concatena a la parte del $query ya definida, con el AND:
                //el nombre de la llave y su valor, de cada elemento recibido en $array
                $query .= " $key = '$value' AND ";
            }
        }

        //ejecuta la consulta con el $query enviado y obtiene resultado
        $resultado = self::consultarSQL($query);
        //retorna arreglo $resultado
        return ( $resultado );
    }

    //obtener de la DB, la cantidad (LIMIT) de registros recibida en $por_pagina, 
    //saltando la cantidad (OFFSET) de registros recibidos en $offset. Orden desc por id
    public static function paginar($por_pagina, $offset ) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT $por_pagina OFFSET $offset " ;
        $resultado = self::consultarSQL($query);
        return ( $resultado );
    }

    // Obtener la cantidad total de registros de una tabla, de la DB
    public static function total() {
        //consulta SQL
        $query = "SELECT COUNT(*) FROM " . static::$tabla;
        //consulta dirécta a la DB, enviando el query SQL,
        //si la conexión ha sido exitosa, retorna un objeto con info de la consulta
        $resultado = self::$db->query($query);
        
        //procesa la info de $resultado y obtiene el resultado de la consulta
        //como un arreglo asociativo o index,
        //donde el el primer elemento es el número total de registros
        $total = $resultado->fetch_array();
        
        //extrae y retorna el prime valor del arreglo, la cantidad de registros
        return array_shift($total);
    }
    
    // Obtener la cantidad total de registros de una tabla, según columna y valor
    public static function total_columna_valor($columna = '', $valor = '') {
        //consulta SQL
        $query = "SELECT COUNT(*) FROM " . static::$tabla;

        if($columna) {
            //agrega al query
            $query .= " WHERE $columna = $valor";
        }
        //consulta dirécta a la DB, enviando el query SQL,
        //si la conexión ha sido exitosa, retorna un objeto con info de la consulta
        $resultado = self::$db->query($query);
        
        //procesa la info de $resultado y obtiene el resultado de la consulta
        //como un arreglo asociativo o index,
        //donde el el primer elemento es el número total de registros
        $total = $resultado->fetch_array();
        
        //extrae y retorna el prime valor del arreglo, la cantidad de registros
        return array_shift($total);
    }

    // crea un nuevo registro
    public function crear() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Insertar en la base de datos
        $query = " INSERT INTO " . static::$tabla . " ( ";
        $query .= join(', ', array_keys($atributos));
        $query .= " ) VALUES ('"; 
        $query .= join("', '", array_values($atributos));
        $query .= " ') ";

        //debuguear($query); // Descomentar si no te funciona algo

        // Resultado de la consulta
        $resultado = self::$db->query($query);
        return [
           'resultado' =>  $resultado,
           'id' => self::$db->insert_id
        ];
    }

    // Actualizar el registro
    public function actualizar() {
        // Sanitizar los datos
        $atributos = $this->sanitizarAtributos();

        // Iterar para ir agregando cada campo de la BD
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }

        // Consulta SQL
        $query = "UPDATE " . static::$tabla ." SET ";
        $query .=  join(', ', $valores );
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
        $query .= " LIMIT 1 "; 

        // Actualizar BD
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Eliminar un Registro por su ID
    public function eliminar() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }
}