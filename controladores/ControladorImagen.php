<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/dragonball/dirs.php";
    require_once CONTROLLER_PATH."ControladorLuchador.php";
    require_once UTILITY_PATH."funciones.php";

class ControladorImagen {
    static private $instancia = null;
    private function __construct() {
        //echo "Conector creado";
    }
    /**
     * Patrón Singleton. Ontiene una instancia del Controlador de Descargas
     * @return instancia de conexion
     */
    public static function getControlador() {
        if (self::$instancia == null) {
            self::$instancia = new ControladorImagen();
        }
        return self::$instancia;
    }
//--------------------------------------------------------------------------------------------
    function salvarImagen($imagen) {
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], IMAGE_PATH ."fotos/" . $imagen)) {
            return true;
        }
        return false;
    }
//--------------------------------------------------------------------------------------------
    function eliminarImagen($imagen) {
        $fichero = IMAGE_PATH ."fotos/" . $imagen;
        if (file_exists($fichero)) {
            unlink($fichero);
            return true;
        }
        return false;;
    }
//--------------------------------------------------------------------------------------------
    function actualizarFoto(){
        $fotoAnterior = trim($_POST["fotoAnterior"]);
        $extension = explode("/", $_FILES['foto']['type']);
        $nombreFoto = md5($_FILES['foto']['tmp_name'] . $_FILES['foto']['name']) . "." . $extension[1];
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], ROOT_PATH . "/fotos/$nombreFoto")) {
            $nombreFoto = $fotoAnterior;
        }
        return $nombreFoto;
    }

}

?>