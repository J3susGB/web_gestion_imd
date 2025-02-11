<?php
namespace Model;
use PDOException;
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

    public function guardar_designacion() {
        if (!is_null($this->id)) {
            // Actualizar si el ID ya existe
            return $this->actualizar();
        } else {
            // Crear un nuevo registro y obtener el ID
            $resultado = $this->crear();
            
            if ($resultado['resultado']) {
                $this->id = $resultado['id']; // Asigna el ID generado
                error_log("ID asignado en guardarConId(): " . $this->id); // Depuración
            } else {
                error_log("Error al guardar la designación");
            }
    
            return $resultado['resultado'];
        }
    }

    public static function truncate($table) {
        try {
            // Deshabilitar claves foráneas para evitar restricciones
            self::$db->query("SET FOREIGN_KEY_CHECKS = 0;");
    
            // Usar DELETE para vaciar la tabla
            $query = "DELETE FROM {$table}";
            self::$db->query($query);
    
            // Restaurar las claves foráneas después de truncar
            self::$db->query("SET FOREIGN_KEY_CHECKS = 1;");
    
            return true; // Operación exitosa
        } catch (PDOException $e) {
            error_log("Error al truncar la tabla '{$table}': " . $e->getMessage());
            return false; // Operación fallida
        }
    }
    

    // Obtener todos los Registros
    public static function all($orden = 'DESC') {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id {$orden}";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Obtener todos los Registros
    public static function all_orden_fecha($orden = 'ASC') {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY fecha {$orden}";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Obtener todos los Registros
    public static function all_apellido1($orden = 'DESC') {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY apellido1 {$orden}";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    public static function all_apellidos_y_nombre($orden1 = 'ASC', $orden2 = 'ASC', $orden3 = 'ASC') {
        $query = "SELECT * FROM " . static::$tabla . " 
                  ORDER BY TRIM(apellido1) {$orden1}, TRIM(apellido2) {$orden2}, TRIM(nombre) {$orden3}";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    
    

    // Busca un registro por su id
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla  ." WHERE id = {$id}";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    // Busca un partido por su id_partido
    public static function encuentra_partido($id_partido) {
        $id_partido = self::$db->escape_string($id_partido); // Asegura escape
        $query = "SELECT * FROM " . static::$tabla . " WHERE id_partido = '{$id_partido}'";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

     // Busca las designaciones por su jornada editada
     public static function encuentra_jornada($jornada_editada) {
        $jornada_editada = self::$db->escape_string($jornada_editada); // Asegura escape
        $query = "SELECT * FROM " . static::$tabla . " WHERE jornada_editada = '{$jornada_editada}'";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Obtener Registros con cierta cantidad
    public static function get($limite) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT {$limite}" ;
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    //Paginar los registros
    public static function paginar($por_pagina, $offset) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id DESC LIMIT {$por_pagina} OFFSET {$offset}" ;

        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busqueda Where con Columna 
    public static function where($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = '{$valor}'";
        $resultado = self::consultarSQL($query);
        return array_shift( $resultado ) ;
    }

    public static function where_all($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = '{$valor}'";
        $resultado = self::consultarSQL($query);
        return $resultado; // Devuelve todos los resultados
    }

    public static function where_all_ordered_by_fecha($columna, $valor) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE {$columna} = '{$valor}' ORDER BY fecha ASC";
        $resultado = self::consultarSQL($query);
        return $resultado; // Devuelve todos los resultados ordenados por fecha ascendente
    }
    
    

    // Retornar los registros por un orden 
    public static function ordenar($columna, $orden) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY {$columna} {$orden}";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Retornar por orden y con un límite:
    public static function ordenarLimite($columna, $orden, $limite) {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY {$columna} {$orden} LIMIT {$limite}";
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Busqueda Where con múltiples opciones 
    public static function whereArray($array = []) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE ";
        foreach($array as $key => $value) {
            if( $key === array_key_last($array) ) { //Evaluará si está en al última llave del array, 
                $query .= " {$key} = '{$value}'"; //Si es la última, no añadirá en AND al final
            } else {
                $query .= " {$key} = '{$value}' AND "; //Si no es la ultima, lo añadirá
            }    
        }

        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // public static function busquedaParcial($busqueda) {
    //     // Asegurar que la búsqueda está limpia
    //     $busqueda = mysqli_real_escape_string(self::$db, $busqueda);
    //     $palabras = explode(' ', $busqueda);
    
    //     // Base de la consulta
    //     $query = "SELECT * FROM " . static::$tabla . " WHERE activo = 1";
    //     $condiciones = [];
    
    //     foreach ($palabras as $palabra) {
    //         $palabra = trim($palabra);
    //         $condiciones[] = "(CONVERT(apellido1 USING utf8mb4) LIKE '%{$palabra}%' 
    //                         OR CONVERT(apellido2 USING utf8mb4) LIKE '%{$palabra}%'
    //                         OR CONVERT(nombre USING utf8mb4) LIKE '%{$palabra}%')";
    //     }
    
    //     // Agregar condiciones
    //     if (count($condiciones) > 0) {
    //         $query .= " AND (" . implode(' AND ', $condiciones) . ")";
    //     }
    
    //     // Ordenar resultados
    //     $query .= " ORDER BY apellido1 ASC, apellido2 ASC, nombre ASC";
    
    //     $resultado = self::consultarSQL($query);
    //     return $resultado;
    // }
    
    public static function busquedaParcial($busqueda) {
        // Asegurar que la búsqueda está limpia
        $busqueda = mysqli_real_escape_string(self::$db, $busqueda);
        $palabras = explode(' ', $busqueda);
    
        // Base de la consulta
        $query = "SELECT * FROM " . static::$tabla . " WHERE activo = 1";
        $condiciones = [];
    
        foreach ($palabras as $palabra) {
            $palabra = trim($palabra);
            $condiciones[] = "(
                CONVERT(apellido1 USING utf8mb4) COLLATE utf8mb4_general_ci LIKE '%{$palabra}%'
                OR CONVERT(apellido2 USING utf8mb4) COLLATE utf8mb4_general_ci LIKE '%{$palabra}%'
                OR CONVERT(nombre USING utf8mb4) COLLATE utf8mb4_general_ci LIKE '%{$palabra}%'
            )";
        }
    
        // Agregar condiciones
        if (count($condiciones) > 0) {
            $query .= " AND (" . implode(' AND ', $condiciones) . ")";
        }
    
        // Ordenar resultados
        $query .= " ORDER BY apellido1 ASC, apellido2 ASC, nombre ASC";
    
        $resultado = self::consultarSQL($query);
        return $resultado;
    }
    
    
    
    

    //Traer un total de registros
    public static function total ($columna = '', $valor = '') {
        $query = "SELECT COUNT(*) FROM " . static::$tabla;
        if( $columna ) {
            $query .= " WHERE {$columna} = {$valor}";
        }
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();

        return array_shift($total); //Array_shift lo saca del array y trae el valor
    }

    //Traer un total de registros con array Where
    public static function totalArray ($array=[]) {
        $query = "SELECT COUNT(*) FROM " . static::$tabla . " WHERE ";
        foreach($array as $key => $value) {
            if( $key === array_key_last($array) ) { //Evaluará si está en al última llave del array, 
                $query .= " {$key} = '{$value}'"; //Si es la última, no añadirá en AND al final
            } else {
                $query .= " {$key} = '{$value}' AND "; //Si no es la ultima, lo añadirá
            }    
        }
        $resultado = self::$db->query($query);
        $total = $resultado->fetch_array();

        return array_shift($total); //Array_shift lo saca del array y trae el valor
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
    $query .= "') ";
 
    // debuguear($query); // Descomentar si no te funciona algo
 
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
    public function eliminar_por_id_partido() {
        $query = "DELETE FROM "  . static::$tabla . " WHERE id_partido = " . self::$db->escape_string($this->id) . " LIMIT 1";
        $resultado = self::$db->query($query);
        return $resultado;
    }
}