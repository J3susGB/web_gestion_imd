<?php

namespace Model;

class Categorias extends ActiveRecord {
    protected static $tabla = 'categorias';
    protected static $columnasDB = ['id', 'nombre', 'nombre2', 'tarifa', 'facturar', 'pago_arbitro', 'oa', 'id_modalidad'];

    public $id;
    public $nombre;
    public $nombre2;
    public $tarifa;
    public $facturar;
    public $pago_arbitro;
    public $oa;
    public $id_modalidad;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->nombre2 = $args['nombre2'] ?? '';
        $this->tarifa = $args['tarifa'] ?? '';
        $this->facturar = $args['facturar'] ?? '';
        $this->pago_arbitro = $args['pago_arbitro'] ?? '';
        $this->oa = $args['oa'] ?? '';
        $this->id_modalidad = $args['id_modalidad'] ?? null;
    }

    public function validar_categoria() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El campo nombre no puede estar vacío';
        }
        if(!$this->nombre2) {
            self::$alertas['error'][] = 'El campo nombre corto no puede estar vacío';
        }
        if(!$this->tarifa === null) {
            self::$alertas['error'][] = 'El campo tarifa no puede estar vacío';
        }
        if(!$this->facturar === null) {
            self::$alertas['error'][] = 'El campo facturar no puede estar vacío';
        }
        if(!$this->pago_arbitro === null) {
            self::$alertas['error'][] = 'El campo pago árbitro no puede estar vacío';
        }
    
        if(!$this->oa === null) {
            self::$alertas['error'][] = 'El campo organización arbitral no puede estar vacío';
        }
    
        return self::$alertas;
    }
}