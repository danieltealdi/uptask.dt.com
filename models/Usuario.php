<?php

namespace Model;

class Usuario extends ActiveRecord
{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','confirmado','token'];
    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
        $this->token = $args['token'] ?? '';
    }
    public function validarNuevaCuenta()
    {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El email no es válido';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los passwords son diferentes';
        }
        return self::$alertas;
    }
    public function validarEmail()
    {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;
    }
    public function validarPassword()
    {
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }
        return self::$alertas;
    }
    public function hashPassword()
    {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    public function crearToken()
    {
        $this->token = uniqid();

    }
    public function validarLogin()
    {
        if(!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        if(!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }
        if(strlen($this->password)<6) {
            self::$alertas['error'][] = 'El password debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }
    public function comprobarPasswordAndVerificado($password){
        //var_dump($this->password);
        //var_dump($password);die;
        $verificado = password_verify($password, $this->password);
        if(!$verificado) {
            self::$alertas['error'][] = 'Password Incorrecto';
        }
        return $verificado;
    }
}
