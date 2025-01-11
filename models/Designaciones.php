<?php

namespace Model;

class Designaciones extends ActiveRecord {
    protected static $tabla = 'designaciones';
    protected static $columnasDB = [
        'id', 'id_usuario', 'id_arbitro', 'id_partido', 'categoria', 'grupo', 
        'fecha', 'hora', 'terreno', 'distrito', 'jornada', 'local', 'visitante', 
        'observaciones', 'modalidad', 'estado', 'unidad', 'jornada_editada', 'oa',
        'pago_arbitro', 'facturar', 'tarifa'
    ];

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
    public $unidad;
    public $jornada_editada;
    public $oa;
    public $pago_arbitro;
    public $facturar;
    public $tarifa;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->id_arbitro = $args['id_arbitro'] ?? '';
        $this->id_partido = $args['id_partido'] ?? '';
        $this->categoria = $args['categoria'] ?? '';
        $this->grupo = $args['grupo'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
        $this->terreno = $args['terreno'] ?? '';
        $this->distrito = $args['distrito'] ?? '';
        $this->jornada = $args['jornada'] ?? '';
        $this->local = $args['local'] ?? '';
        $this->visitante = $args['visitante'] ?? '';
        $this->observaciones = $args['observaciones'] ?? '';
        $this->modalidad = $args['modalidad'] ?? '';
        $this->estado = $args['estado'] ?? 0;
        $this->unidad = $args['unidad'] ?? 1;
        $this->jornada_editada = $args['jornada_editada'] ?? 0;
        $this->oa = $args['oa'] ?? 0.00;
        $this->pago_arbitro = $args['pago_arbitro'] ?? 0.00;
        $this->facturar = $args['facturar'] ?? 0.00;
        $this->tarifa = $args['tarifa'] ?? 0.00;
    }

    public function validar_generar_designacion() {

        if (!$this->jornada_editada) {
            self::$alertas['error'][] = 'El campo jornada no puede estar vacío';
        }
    
        return self::$alertas;
    }

    public function validar_designacion() {
        if (!$this->id_usuario) {
            self::$alertas['error'][] = 'El campo usuario no puede estar vacío';
        }
        if (!$this->id_arbitro) {
            self::$alertas['error'][] = 'El campo arbitro no puede estar vacío';
        }
        if (!$this->id_partido) {
            self::$alertas['error'][] = 'El campo ID partido no puede estar vacío';
        }
        if (!$this->categoria) {
            self::$alertas['error'][] = 'El campo categoria no puede estar vacío';
        }
        if (!$this->grupo) {
            self::$alertas['error'][] = 'El campo grupo no puede estar vacío';
        }
        if (!$this->fecha) {
            self::$alertas['error'][] = 'El campo fecha no puede estar vacío';
        }
        if (!$this->hora) {
            self::$alertas['error'][] = 'El campo hora no puede estar vacío';
        }
        if (!$this->terreno) {
            self::$alertas['error'][] = 'El campo terreno no puede estar vacío';
        }
        if (!$this->distrito) {
            self::$alertas['error'][] = 'El campo distrito no puede estar vacío';
        }
        if (!$this->jornada) {
            self::$alertas['error'][] = 'El campo jornada no puede estar vacío';
        }
        if (!$this->local) {
            self::$alertas['error'][] = 'El campo local no puede estar vacío';
        }
        if (!$this->visitante) {
            self::$alertas['error'][] = 'El campo visitante no puede estar vacío';
        }
        if (!$this->modalidad) {
            self::$alertas['error'][] = 'El campo modalidad no puede estar vacío';
        }
        if (!$this->estado) {
            self::$alertas['error'][] = 'El campo estado no puede estar vacío';
        }
        if (!$this->unidad) {
            self::$alertas['error'][] = 'El campo unidad no puede estar vacío';
        }
        if (!$this->jornada_editada) {
            self::$alertas['error'][] = 'El campo jornada real no puede estar vacío';
        }

        return self::$alertas;
    }
}