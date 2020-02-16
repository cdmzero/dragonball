<?php
class luchador {
    private $id;
    private $nombre;
    private $raza;
    private $ki;
    private $transformacion;
    private $ataque;
    private $planeta;
    private $password;
    private $fecha;
    private $imagen;

    
    // Constructor
    public function __construct($id, $nombre, $raza, $ki, $transformacion, $ataque, $planeta, $password, $fecha, $imagen) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->raza = $raza;
        $this->ki = $ki;
        $this->transformacion = $transformacion;
        $this->ataque = $ataque;
        $this->planeta = $planeta;
        $this->password = $password;
        $this->fecha = $fecha;
        $this->imagen = $imagen;
    }
    
    // **** GETS & SETS
    function getid() {
        return $this->id;
    }

    function getnombre() {
        return $this->nombre;
    }

    
    function getraza() {
        return $this->raza;
    }

    function getki() {
        return $this->ki;
    }

    function gettransformacion() {
        return $this->transformacion;
    }

    function getataque() {
        return $this->ataque;
    }

    function getplaneta() {
        return $this->planeta;
    }

    function getpassword() {
        return $this->password;
    }

    function getfecha() {
        return $this->fecha;
    }

    function getimagen() {
        return $this->imagen;
    }

    function setid($id) {
        $this->id = $id;
    }

    function setnombre($nombre) {
        $this->nombre = $nombre;
    }

    function setraza($raza) {
        $this->raza = $raza;
    }
    
    function setki($ki) {
        $this->ki = $ki;
    } 

    function settransformacion($transformacion) {
        $this->transformacion = $transformacion;
    } 

    function setataque($ataque) {
        $this->ataque = $ataque;
    } 

    function setplaneta($planeta) {
        $this->planeta = $planeta;
    }
    
    function setpassword($password) {
        $this->password = $password;
    }

    function setfecha($fecha) {
        $this->fecha = $fecha;
    }

    function setimagen($imagen) {
        $this->imagen = $imagen;
    } 
}
?>