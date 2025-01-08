<?php

namespace Model;

class Arbitros extends ActiveRecord {
    protected static $tabla = 'arbitros';
    protected static $columnasDB = ['id', 'nombre', 'apellido1', 'apellido2', 'email', 'telefono', 'coche', 'codigo_postal', 'modalidad', 'activo'];

    public $id;
    public $nombre;
    public $apellido1;
    public $apellido2;
    public $email;
    public $telefono;
    public $coche;
    public $codigo_postal;
    public $modalidad;
    public $activo;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido1 = $args['apellido1'] ?? '';
        $this->apellido2 = $args['apellido2'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->coche = $args['coche'] ?? 0;
        $this->codigo_postal = $args['codigo_postal'] ?? '';
        $this->modalidad = $args['modalidad'] ?? '';
        $this->activo = $args['activo'] ?? TRUE;
    }

    public function validar_arbitro() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El campo nombre no puede estar vacío';
        }
        if(!$this->apellido1) {
            self::$alertas['error'][] = 'El campo primer apellido no puede estar vacío';
        }
        if(!$this->apellido2) {
            self::$alertas['error'][] = 'El campo segundo apellido no puede estar vacío';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El campo email no puede estar vacío';
        }
        if(!$this->telefono) {
            self::$alertas['error'][] = 'El campo telefono no puede estar vacío';
        }

        if (strlen($this->telefono) !== 9) {
            self::$alertas['error'][] = 'El teléfono no es válido';
        }
    
        // Verificar que coche y modalidad no sean nulos
        if($this->coche === null) {
            self::$alertas['error'][] = 'El campo coche no puede estar vacío';
        }
        if($this->modalidad === null) {
            self::$alertas['error'][] = 'El campo modalidad no puede estar vacío';
        }
    
        if(!$this->codigo_postal) {
            self::$alertas['error'][] = 'El campo código postal no puede estar vacío';
        }

        if (strlen($this->codigo_postal) !== 5) {
            self::$alertas['error'][] = 'El código postal no es válido';
        }
    
        return self::$alertas;
    }
}