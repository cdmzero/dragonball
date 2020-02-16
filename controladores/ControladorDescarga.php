<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/dragonball/dirs.php";
require_once CONTROLLER_PATH . "ControladorLuchador.php";
require_once MODEL_PATH . "luchador.php";
require_once VENDOR_PATH . "autoload.php";
use Spipu\Html2Pdf\HTML2PDF;

class ControladorDescarga
{
    private $fichero;
    static private $instancia = null;

    private function __construct()
    {
        //echo "Conector creado";
    }

    /**
     * Patrón Singleton. Ontiene una instancia del Controlador de Descargas
     * @return instancia de conexion
     */

    public static function getControlador()
    {
        if (self::$instancia == null) {
            self::$instancia = new ControladorDescarga();
        }
        return self::$instancia;
    }
//----------------------------------------------------------------------------------------------------------
    public function descargarTXT()
    {
        $this->fichero = "dragones.txt";
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $this->fichero . "");

        $controlador = ControladorLuchador::getControlador();
        $lista = $controlador->listarLuchador("", "");

        if (!is_null($lista) && count($lista) > 0) {
            foreach ($lista as &$dragon) {
                echo " -- Nombre: " . $dragon->getNombre() . "  -- Raza: " . $dragon->getraza() .
                    " -- KI: " . $dragon->getki() . " -- transformacion: " . $dragon->gettransformacion() . " -- Ataque: " . $dragon->getataque() .
                    " --Planeta: " . $dragon->getplaneta() . " -- Password: " . $dragon->getpassword(). " -- Fecha: " . $dragon->getfecha() ."\r\n";
            }
        } else {
            echo "No se ha encontrado datos de dragones";
        }
    }
//---------------------------------------------------------------------------------------------------------
    public function descargarJSON()
    {
        $this->fichero = "dragones.json";
        header("Content-Type: application/octet-stream");
        header('Content-type: application/json');
        //header("Content-Disposition: attachment; filename=" . $this->fichero . ""); //archivo de salida

        $controlador = ControladorLuchador::getControlador();
        $lista = $controlador->listarLuchador("", "");
        $sal = [];
        foreach ($lista as $al) {
            $sal[] = $this->json_encode_private($al);
        }
        echo json_encode($sal);
    }

    private function json_encode_private($object)
    {
        $public = [];
        $reflection = new ReflectionClass($object);
        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $public[$property->getName()] = $property->getValue($object);
        }
        return json_encode($public);
    }
//-----------------------------------------------------------------------------------------------------------
    public function descargarXML()
    {
        $this->fichero = "dragones.xml";
        $nombre= "listado.xml";
        $lista = $controlador = ControladorLuchador::getControlador();
        $lista = $controlador->listarLuchador("", "");
        $doc = new DOMDocument('1.0', 'UTF-8');
        $luchadores = $doc->createElement('luchadores');

        foreach ($lista as $a) {
            $luchador = $doc->createElement('luchador');
            $luchador->appendChild($doc->createElement('nombre', $a->getnombre()));
            $luchador->appendChild($doc->createElement('raza', $a->getraza()));
            $luchador->appendChild($doc->createElement('ki', $a->getki()));
            $luchador->appendChild($doc->createElement('transformacion', $a->gettransformacion()));
            $luchador->appendChild($doc->createElement('ataque', $a->getataque()));
            $luchador->appendChild($doc->createElement('planeta', $a->getplaneta()));
            $luchador->appendChild($doc->createElement('password', $a->getpassword()));
            $luchador->appendChild($doc->createElement('fecha', $a->getfecha()));
            $luchador->appendChild($doc->createElement('imagen', $a->getimagen()));

            $luchadores->appendChild($luchador);
        }

        $doc->appendChild($luchadores);
        header('Content-type: application/xml');
        header("Content-Disposition: attachment; filename=" . $nombre . ""); //archivo de salida
        echo $doc->saveXML();

        exit;
    }
//-------------------------------------------------------------------------------------------------------------
    public function descargarPDF(){
        ob_end_clean(); // Para limpiar de polvo paja y alpiste, muy util de hecho voy a invitar a un cafe a quien me dio la solucion
        $sal ='<h2 class="pull-left">Fichas de Luchadores</h2>';
        $lista = $controlador = ControladorLuchador::getControlador();
        $lista = $controlador->listarLuchador("", "");
        if (!is_null($lista) && count($lista) > 0) {
            $sal.="<table class='table table-bordered table-striped'>";
            $sal.="<thead>";
            $sal.="<tr>";
            $sal.="<th>Nombre</th>";
            $sal.="<th>Raza</th>";
            $sal.="<th>KI</th>";
            $sal.="<th>Transformacion</th>";
            $sal.="<th>Ataque</th>";
            $sal.="<th>Planeta</th>";
            //$sal.="<th>Password</th>";
            $sal.="<th>Fecha</th>";
            $sal.="<th>Imagen</th>";
            $sal.="</tr>";
            $sal.="</thead>";
            $sal.="<tbody>";

            foreach ($lista as $a) {
                $sal.="<tr>";
                $sal.="<td>" . $a->getnombre() . "</td>";
                $sal.="<td>" . $a->getraza() . "</td>";
                $sal.="<td>" . $a->getki() . "</td>";
                $sal.="<td>" . $a->gettransformacion() . "</td>";
                $sal.="<td>" . $a->getataque() . "</td>";
                $sal.="<td>" . $a->getplaneta() . "</td>";
                //$sal.="<td>" . str_repeat("*",strlen($dragon->getPassword())) . "</td>";
                $sal.="<td>" . $a->getfecha() . "</td>";
                // Para sacar una imagen hay que decirle el directorio real donde está
                $sal.="<td><img src='".$_SERVER['DOCUMENT_ROOT'] . "/dragonball/imagenes/fotos/" . $a->getimagen()."'  style='max-width: 12mm; max-height: 12mm'></td>";
                $sal.="</tr>";
            }
            $sal.="</tbody>";
            $sal.="</table>";
        } else {
            $sal.="<p class='lead'><em>No se ha encontrado datos de dragones/as.</em></p>";
        }
        $pdf=new HTML2PDF('L','A4','es','false','UTF-8');
        $pdf->writeHTML($sal);
        $pdf->output('dragones.pdf');

    }
}
