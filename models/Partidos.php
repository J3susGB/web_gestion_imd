<?php

namespace Model;

class Partidos extends ActiveRecord {
    protected static $tabla = 'partidos';
    protected static $columnasDB = ['id', 'id_usuario', 'id_arbitro', 'id_partido', 'categoria', 'grupo', 'fecha', 'hora', 'terreno', 'distrito', 'jornada', 'local', 'visitante', 'observaciones', 'modalidad', 'estado', 'id_designacion'];

    public $id;
    public $id_usuario;
    public $id_arbitro;
    public $id_partido;
    public $categoria;
    public $grupo;
    public $fecha;
    public $hora;
    public $terreno;
    public $distrito;
    public $jornada;
    public $local;
    public $visitante;
    public $observaciones;
    public $modalidad;
    public $estado;
    public $id_designacion;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? 0;
        $this->id_arbitro = $args['id_arbitro'] ?? 0;
        $this->id_partido = $args['id_partido'] ?? '';
        $this->categoria = $args['categoria'] ?? '';
        $this->grupo = $args['grupo'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->terreno = $args['terreno'] ?? 0;
        $this->distrito = $args['distrito'] ?? '';
        $this->jornada = $args['jornada'] ?? '';
        $this->local = $args['local'] ?? '';
        $this->visitante = $args['visitante'] ?? '';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->modalidad = $args['modalidad'] ?? 0;
        $this->estado = $args['estado'] ?? 0;
        $this->id_designacion = $args['id_designacion'] ?? 0;
    }

    public function validar_partido() {

        if (!$this->id_partido) {
            self::$alertas['error'][] = 'El campo id partido no puede estar vacío';
        }
    
        if (!$this->categoria) {
            self::$alertas['error'][] = 'El campo categoría no puede estar vacío';
        }
    
        if (!$this->grupo) {
            self::$alertas['error'][] = 'El campo grupo no puede estar vacío';
        }
    
        if (!$this->fecha) {
            self::$alertas['error'][] = 'El campo fecha no puede estar vacío';
        } elseif (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $this->fecha) || strlen($this->fecha) !== 10) {
            self::$alertas['error'][] = 'El campo fecha debe tener el formato dd/mm/yyyy y exactamente 10 caracteres';
        }
    
        if (!$this->hora) {
            self::$alertas['error'][] = 'El campo hora no puede estar vacío';
        } elseif (!preg_match('/^\d{1,2}:\d{2}$/', $this->hora) || strlen($this->hora) < 4 || strlen($this->hora) > 5) {
            self::$alertas['error'][] = 'El campo hora debe tener el formato hh:mm y entre 4 y 5 caracteres';
        }
    
        if (!$this->terreno) {
            self::$alertas['error'][] = 'El campo terreno no puede estar vacío';
        }
    
        if (!$this->distrito) {
            self::$alertas['error'][] = 'El campo distrito no puede estar vacío';
        }
    
        if (!isset($this->jornada)) {
            self::$alertas['error'][] = 'El campo jornada no puede estar vacío';
        } elseif (!ctype_digit((string)$this->jornada) || strlen((string)$this->jornada) < 1 || strlen((string)$this->jornada) > 2) {
            self::$alertas['error'][] = 'El campo jornada debe contener únicamente números y tener entre 1 y 2 caracteres';
        }
    
        if (!$this->local) {
            self::$alertas['error'][] = 'El campo local no puede estar vacío';
        }
    
        if (!$this->visitante) {
            self::$alertas['error'][] = 'El campo visitante no puede estar vacío';
        }
    
    
        if (!isset($this->modalidad)) {
            self::$alertas['error'][] = 'El campo modalidad no puede estar vacío';
        }
    
        return self::$alertas;
    }

    // public static function filtrar($filtros) {
    //     $query = "SELECT * FROM partidos WHERE 1=1";
    
    //     if (!empty($filtros['arbitro'])) {
    //         $arbitro = strtolower($filtros['arbitro']);
    //         $query .= " AND LOWER(REPLACE(arbitro, 'Á', 'A')) LIKE '%$arbitro%'";
    //     }
    
    //     if (!empty($filtros['id_partido'])) {
    //         $query .= " AND id_partido = '{$filtros['id_partido']}'";
    //     }
    
    //     if (!empty($filtros['campo'])) {
    //         $campo = strtolower($filtros['campo']);
    //         $query .= " AND LOWER(terreno) LIKE '%$campo%'";
    //     }
    
    //     if (!empty($filtros['equipo'])) {
    //         $equipo = strtolower($filtros['equipo']);
    //         $query .= " AND (LOWER(local) LIKE '%$equipo%' OR LOWER(visitante) LIKE '%$equipo%')";
    //     }
    
    //     if (!empty($filtros['categoria'])) {
    //         $query .= " AND categoria = '{$filtros['categoria']}'";
    //     }
    
    //     if (!empty($filtros['fecha'])) {
    //         $query .= " AND fecha = '{$filtros['fecha']}'";
    //     }
    
    //     if (!empty($filtros['distrito'])) {
    //         $query .= " AND distrito = '{$filtros['distrito']}'";
    //     }
    
    //     if (!empty($filtros['modalidad'])) {
    //         $query .= " AND modalidad = '{$filtros['modalidad']}'";
    //     }
    
    //     if (!empty($filtros['estado'])) {
    //         $query .= " AND designado = '{$filtros['estado']}'";
    //     }
    
    //     return self::consultarSQL($query);
    // }
    
    
}