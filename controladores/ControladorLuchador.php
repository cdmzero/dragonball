<?php
require_once MODEL_PATH."luchador.php";
require_once CONTROLLER_PATH."ControladorBD.php";
require_once UTILITY_PATH."funciones.php";

class ControladorLuchador {

    static private $instancia = null;
    private function __construct() {
        //echo "Conector creado";
    }
    
    /**
     * Patrón Singleton. Ontiene una instancia del Manejador de la BD
     * @return instancia de conexion
     */
    public static function getControlador() {
        if (self::$instancia == null) {
            self::$instancia = new ControladorLuchador();
        }
        return self::$instancia;
    }
    
    /**
     * Lista el alumnado según el nombre o raza
     * @param type $nombre
     * @param type $raza
     */
//----------------------------------------------------------------------------------------------------
    public function listarLuchador($nombre, $raza){
        $lista=[];
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();

        $consulta = "SELECT * FROM luchadores WHERE nombre LIKE :nombre OR raza LIKE :raza";
        $parametros = array(':nombre' => "%".$nombre."%", ':raza' => "%".$raza."%");

        $res = $bd->consultarBD($consulta,$parametros);
        $filas=$res->fetchAll(PDO::FETCH_OBJ);

        if (count($filas) > 0) {
            foreach ($filas as $a) {
                $luchador = new luchador($a->id, $a->nombre, $a->raza, $a->ki, $a->transformacion, $a->ataque, $a->planeta, $a->password, $a->fecha, $a->imagen);
                $lista[] = $luchador;
            }
            $bd->cerrarBD();
            return $lista;
        }else{
            return null;
        }    
    }
//----------------------------------------------------------------------------------------------------
    public function almacenarLuchador( $nombre, $raza, $ki, $transformacion, $ataque, $planeta, $password, $fecha, $imagen){
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "INSERT INTO luchadores (nombre, raza, ki, transformacion, 
            ataque, planeta, password, fecha, imagen) VALUES ( :nombre, :raza, :ki, :transformacion, :ataque, 
            :planeta, :password, :fecha, :imagen)";
        
        $parametros= array( ':nombre'=>$nombre, ':raza'=>$raza, ':ki'=>$ki,':transformacion'=>$transformacion,
                            ':ataque'=>$ataque, ':planeta'=>$planeta, ':password'=>$password, ':fecha'=>$fecha, ':imagen'=>$imagen);
        $estado = $bd->actualizarBD($consulta,$parametros);
        $bd->cerrarBD();
        return $estado;
    }
//----------------------------------------------------------------------------------------------------
    public function buscarLuchadorid($id){ 
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "SELECT* FROM luchadores WHERE id = :id";
        $parametros = array(':id' => $id);
        $filas = $bd->consultarBD($consulta, $parametros);
        $res = $bd->consultarBD($consulta,$parametros);
        $filas=$res->fetchAll(PDO::FETCH_OBJ);
        if (count($filas) > 0) {
            foreach ($filas as $a) {
                $luchador = new luchador($a->id,  $a->nombre, $a->raza, $a->ki, $a->transformacion, $a->ataque, $a->planeta, $a->password, $a->fecha, $a->imagen);
            }
            $bd->cerrarBD();
            return $luchador;
        }else{
            return null;
        }    
    }
//--------------------------------------------------------------------------------------------------
    public function buscarLuchador($nombre){ 
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "SELECT * FROM luchadores  WHERE nombre = :nombre";
        $parametros = array(':nombre' => $nombre);
        $filas = $bd->consultarBD($consulta, $parametros);
        $res = $bd->consultarBD($consulta,$parametros);
        $filas=$res->fetchAll(PDO::FETCH_OBJ);
        if (count($filas) > 0) {
            foreach ($filas as $a) {
                $dragon = new luchador($a->id, $a->nombre, $a->raza, $a->ki, $a->transformacion, $a->ataque, $a->planeta, $a->password, $a->fecha, $a->imagen);
            }
            $bd->cerrarBD();
            return $luchador;
        }else{
            return null;
        }    
    }
//------------------------------------------------------------------------------------------------- 
    public function borrarLuchador($id){ 
        $estado=false;
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "DELETE FROM luchadores WHERE id = :id";
        $parametros = array(':id' => $id);
        $estado = $bd->actualizarBD($consulta,$parametros);
        $bd->cerrarBD();
        return $estado;
    }
//-------------------------------------------------------------------------------------------------  
    public function actualizarLuchador($id, $nombre, $raza, $ki, $transformacion, $ataque, $planeta, $password, $fecha, $imagen){
        $bd = ControladorBD::getControlador();
        $bd->abrirBD();
        $consulta = "UPDATE luchadores SET  nombre=:nombre, raza=:raza, ki=:ki, transformacion=:transformacion, ataque=:ataque, 
            planeta=:planeta, password=:password, fecha=:fecha, imagen=:imagen 
            WHERE id=:id";
        $parametros= array(':id' => $id, ':nombre'=>$nombre, ':raza'=>$raza, ':ki'=>$ki,':transformacion'=>$transformacion,
                            ':ataque'=>$ataque, ':planeta'=>$planeta, ':password'=>$password, ':fecha'=>$fecha, ':imagen'=>$imagen);
        $estado = $bd->actualizarBD($consulta,$parametros);
        $bd->cerrarBD();
        return $estado;
    }
    
}
