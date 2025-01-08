<?php

namespace Model;

class Perfiles extends ActiveRecord {
    protected static $tabla = 'perfiles';
    protected static $columnasDB = ['id', 'nombre', 'apellido1', 'apellido2', 'usuario', 'email', 'admin', 'password', 'activo'];

    public $id;
    public $nombre;
    public $apellido1;
    public $apellido2;
    public $usuario;
    public $email;
    public $admin;
    public $password;
    public $activo;
    
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido1 = $args['apellido1'] ?? '';
        $this->apellido2 = $args['apellido2'] ?? '';
        $this->usuario = $args['usuario'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->admin = $args['admin'] ?? 0;
        $this->password = $args['password'] ?? '';
        $this->activo = $args['activo'] ?? TRUE;
    }

    // Validar el Login de Usuarios
    public function validarLogin() {
        if(!$this->usuario) {
            self::$alertas['error'][] = 'El Usuario es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'Debes introducir la contraseña';
        }
        return self::$alertas;

    }

    // Validación para cuentas nuevas
    public function validar_perfil() {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El campo nombre no puede estar vacío';
        }
        if(!$this->apellido1) {
            self::$alertas['error'][] = 'El campo primer apellido no puede estar vacío';
        }
        if(!$this->apellido2) {
            self::$alertas['error'][] = 'El campo segundo apellido no puede estar vacío';
        }
        if(!$this->usuario) {
            self::$alertas['error'][] = 'El campo usuario no puede estar vacío';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El campo email no puede estar vacío';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        // Verificar que admin no sea nulo
        if($this->admin === null) {
            self::$alertas['error'][] = 'El campo administrador no puede estar vacío';
        }
        if($this->password === null) {
            self::$alertas['error'][] = 'El campo contraseña no puede estar vacío';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }
        if($this->activo === null) {
            self::$alertas['error'][] = 'El campo activo no puede estar vacío';
        }
    
        return self::$alertas;
    }

    // Valida un email
    public function validarEmail() {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }

    // Valida el Password 
    public function validarPassword() {
        if(!$this->password) {
            self::$alertas['error'][] = 'El Password no puede ir vacio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function nuevo_password() : array {
        if(!$this->password_actual) {
            self::$alertas['error'][] = 'El Password Actual no puede ir vacio';
        }
        if(!$this->password_nuevo) {
            self::$alertas['error'][] = 'El Password Nuevo no puede ir vacio';
        }
        if(strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    public function validar_reseteo() : array {
        if(!$this->password) {
            self::$alertas['error'][] = 'El campo contraseña no puede ir vacio';
        }
        if(!$this->password2) {
            self::$alertas['error'][] = 'El campo repetir contraseña no puede ir vacio';
        }
        if($this->password2 !== $this->password) {
            self::$alertas['error'][] = 'Las contraseñas deben coincidir';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'La contraseña debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    // Comprobar el password
    public function comprobar_password() : bool {
        return password_verify($this->password_actual, $this->password );
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken() : void {
        $this->token = uniqid();
    }
}