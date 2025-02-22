<?php

namespace Model;

class Categorias extends ActiveRecord {
    protected static $tabla = 'modalidad';
    protected static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}