<?php

namespace Model;

class Restricciones extends ActiveRecord {
    protected static $tabla = 'restricciones';
    protected static $columnasDB = ['id', 'modalidad', 'numero_partidos', 'numero_metalico', 'activo'];

    public $id;
    public $modalidad;
    public $numero_partidos;
    public $numero_metalico;
    public $activo;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->modalidad = $args['modalidad'] ?? 1;
        $this->numero_partidos = $args['numero_partidos'] ?? 0;
        $this->numero_metalico = $args['numero_metalico'] ?? 0;
        $this->activo = $args['activo'] ?? 0;
    }
}