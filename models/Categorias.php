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
}