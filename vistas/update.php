<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/dragonball/dirs.php";
require_once CONTROLLER_PATH . "ControladorLuchador.php";
require_once CONTROLLER_PATH . "ControladorImagen.php";
require_once UTILITY_PATH . "funciones.php";

error_reporting(E_ALL & ~(E_STRICT|E_NOTICE));
session_start();
if (!isset($_SESSION['email']) && empty($_SESSION['email'])) {
    header("location: login.php");
    exit();
}elseif(isset($_SESSION['email']) && !strstr($_SESSION['email'],'@admin.com') ){
    header("location: error.php");
    exit();
}

$nombre = $raza = $ki = $transformacion = $ataque =  $planeta = $password = $fecha = $imagen = "";
$Valnombre = $Valraza = $Valki = $Valtransformacion = $Valataque = $Valplaneta = $Valpassword = $Valfecha  = "";
$Errnombre = $Errraza = $Errki = $Errtransformacion = $Errataque = $Errplaneta = $Errpassword = $Errfecha =  $Errimagen = "";
$errores = [];
$Infoimagen= "";
$imagenAnterior= "";



if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $id = $_POST["id"];

  // NOMBRE
  $Valnombre = filtrado(($_POST["nombre"]));
  if (empty($Valnombre)) {
      $Errnombre = "Por favor introduzca un nombre válido con solo carácteres alfabéticos.";
      $errores[] = $Errnombre ;
  } elseif (!preg_match("/^([A-Za-zÑñ]+[áéíóú]?[A-Za-z]*){3,18}\s?([A-Za-zÑñ]+[áéíóú]?[A-Za-z]*){3,36}$/iu", $Valnombre)) {
      $Errnombre = "Por favor introduzca un nombre válido con solo carácteres alfabéticos validos.";
      $errores[] = $Errnombre ;
  } else {
      $nombre = $Valnombre;
  }
  
  $nombreAnterior = $_POST['nombreAnterior'];

   $controlador = ControladorLuchador::getControlador();
   $luchador = $controlador->buscarLuchador($nombre);

   if (isset($luchador) && $nombreAnterior != $nombre) {

    $Errnombre = "El nombre elegido esta cogido, debes seleccionar otro";
    $errores[] = $Errnombre;

    } elseif($nombreAnterior == $nombre){
        $nombre = $nombreAnterior;
    }elseif(empty($luchador) && $nombreAnterior != $nombre){
        $nombre = $Valnombre;
    }



    //raza
    $Valraza = filtrado($_POST["raza"]);
    if (empty($Valraza)) {
        $Errraza = "Debe elegir al menos una raza";
        $errores[]= $Errraza ;
    } else {
        $raza = $Valraza;
    }

    //KI
    $Valki = $_POST["ki"];
    if (empty($Valki)) {
        $Errki = "Introduzca una ki valida desde el 100 hasta el 999";
        $errores[] = $Errki;
    } elseif (!preg_match("/^([1-9])([0-9]){2}$/", $Valki)) {
        $Errki = "Introduzca una ki valida desde el 100 hasta el 999";
        $errores[] = $Errki;
    } else {
        $ki = $Valki;
    }

    //transformacion
    $Valtransformacion = filtrado($_POST["transformacion"]);
    if (empty($Valtransformacion)) {
        $Errtransformacion = "Debe elegir al menos una transformacion";
        $errores[] = $Errtransformacion;
    } else {
        $transformacion = $Valtransformacion;
    }

    //ataque
    $Valataque=$_POST["ataque"];
    if (empty($Valataque)) {
        $Errataque = "Debe elegir al menos un ataque";
        $errores[] = $Errataque;
    } else {
        $ataque = implode(", ",$_POST["ataque"]);
    }


    //planeta
    $Valplaneta = filtrado($_POST["planeta"]);
    if (empty($Valplaneta)) {
        $Errplaneta = "Introduzca un planeta";
        $errores[] = $Errplaneta;
    } elseif (!preg_match("/([A-zÀ-ž]){3}([0-9]){2}/", $Valplaneta)) {
        $Errplaneta = "Introduzca un planeta valido Formato: Letra+Letra+Letra+Numero+Numero";
        $errores[] = $Errplaneta;
    } else {
        $planeta = $Valplaneta;
    }
    //password
    $passwordAnterior = decode($_POST['passwordAnterior']);
    $Valpassword = $_POST["password"];
 
    if($Valpassword != "*****" && $Valpassword != $passwordAnterior  ){
                     if(empty($Valpassword) || strlen($Valpassword)<5){
                         $Errpassword = "Por favor introduzca password válido y que sea mayor que 5 caracteres.";
                         $errores[]= $Errpassword;
                     } else{
                         $password= hash('md5',$Valpassword);
                     }
     }elseif($Valpassword == "*****" || $Valpassword == $passwordAnterior){
     $password = $passwordAnterior;
    }

    //fecha
    $fecha = date("d-m-Y", strtotime(filtrado($_POST["fecha"])));
    $hoy = date("d-m-Y", time());

    // Comparamos las fechas
    $fecha_mat = new DateTime($fecha);
    $fecha_hoy = new DateTime($hoy);
    $interval = $fecha_hoy->diff($fecha_mat);

    if ($interval->format('%R%a días') > 0) {
        $Errfecha  = "La fecha no puede ser superior a la fecha actual";
        $errores[] =  $Errfecha;
    } else {
        $fecha = date("d/m/Y", strtotime($fecha));
    }

    //imagen
    if ($_FILES['imagen']['size'] > 0 && count($errores) == 0) {
        $propiedades = explode("/", $_FILES['imagen']['type']);
        $extension = $propiedades[1];
        $tam_max = 50000; // 50 KBytes
        $tam = $_FILES['imagen']['size'];
        $mod = true;

        if ($extension != "jpg" && $extension != "jpeg") {
            $mod = false;
            $imagenErr = "Formato debe ser jpg/jpeg";
        }

        if ($tam > $tam_max) {
            $mod = false;
            $imagenErr = "Tamaño superior al limite de: " . ($tam_max / 1000) . " KBytes";
        }

        if ($mod) {
            // guardar
            $imagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name'] . time()) . "." . $extension;
            $controlador = ControladorImagen::getControlador();
            if (!$controlador->salvarImagen($imagen)) {
                $Errimagen = "Error al procesar la imagen y subirla al servidor";
                $errores[] = $Errimagen;
            }

            // Borrar
            $imagenAnterior = trim($_POST["imagenAnterior"]);
            if ($imagenAnterior != $imagen) {
                if (!$controlador->eliminarImagen($imagenAnterior)) {
                    $Infoimagen = "Error al borrar la antigua imagen en el servidor";
                }
            }
        } else {
            // Si no la hemos modificado
            $imagen = trim($_POST["imagenAnterior"]);
        }
    } else {
        $imagen = trim($_POST["imagenAnterior"]);
    }

    if (empty($errores)){
        $controlador = ControladorLuchador::getControlador();
        $estado = $controlador->actualizarLuchador($id, $nombre, $raza, $ki, $transformacion, $ataque, $planeta, $password, $fecha, $imagen);
        if ($estado) {
            alerta("Se ha creado correctamente. $Infoimagen","../index.php");
            exit();
        } else {
            alerta("Ha fallado la modificacion");
            exit();
        }
    } else {
        alerta("Hay errores al procesar el formulario revise los errores");
    }
}



if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id =  decode($_GET["id"]);
    $controlador = ControladorLuchador::getControlador();
    $luchador = $controlador->buscarLuchadorid($id);
    if (!is_null($luchador)) {
        $nombre = $luchador->getnombre();
        $nombreAnterior = $nombre;
        $raza = $luchador->getraza();
        $ki = $luchador->getki();
        $transformacion = $luchador->gettransformacion();
        $ataque = $luchador->getataque();
        $planeta = $luchador->getplaneta();
        $password = $luchador->getpassword();
        $passwordAnterior = $password;
        $fecha = $luchador->getfecha();
        $imagen = $luchador->getimagen();
        $imagenAnterior = $imagen;
    } else {
        header("location: error.php");
        exit();
    }
} else {
    header("location: error.php");
    exit();
}

?>

<?php require_once VIEW_PATH . "navbar.php"; ?>
<head>
<style type="text/css">

.banner-section{background-image:url("../imagenes/update.jpg"); background-size:1500px 350px ; height: 380px; left: 0; position: absolute; top: 0; background-position:0; background-repeat: no-repeat; }
#cabecera {
    font-weight: bold;
  font-size: 68px;
  font-family: "Arial";
  text-shadow: 0 1px 0 #ccc, 
               0 2px 0 #c9c9c9,
               0 3px 0 #bbb,
               0 4px 0 #b9b9b9,
               0 5px 0 #aaa,
               0 6px 1px rgba(0,0,0,.1),
               0 0 5px rgba(0,0,0,.1),
               0 1px 3px rgba(0,0,0,.3),
               0 3px 5px rgba(0,0,0,.2),
               0 5px 10px rgba(0,0,0,.25),
               0 10px 10px rgba(0,0,0,.2),
               0 20px 20px rgba(0,0,0,.15);
  color: #FFF;
  text-align: center;}
  #menu {
  font-weight: bold;
  font-size: 20px;
  font-family: "Arial";
  text-shadow: 0 0.5px 0 #ccc, 
               0 1px 0 #c9c9c9,
               0 1.5px 0 #bbb,
               0 2px 0 #b9b9b9,
               0 2.5px 0 #aaa,
               0 3px 0.5px rgba(0,0,0,.1),
               0 0 2.4px rgba(0,0,0,.1),
               0 0.5px 1.5px rgba(0,0,0,.3),
               0 1.5px 2.5px rgba(0,0,0,.2),
               0 2.5px 5px rgba(0,0,0,.25),
               0 5px 5px rgba(0,0,0,.2),
               0 10px 10px rgba(0,0,0,.1);
  color: #FFF;}
</style>
</head>
<section class="post-content-section">
    <div class="container">
        <div class="row" >
            <div class="col-lg-12 col-md-12 col-sm-12 post-title-block">
            <div id='cabecera'> <h1 class="display-1 text-center">Modificar Luchador</h1> </div>
            <div id="menu">    
            <ul class="list-inline text-center">
               
                    <li>Jose F |</li>
                    <li>CRUD Dragon Ball  |</li>
                    <li>Modificar</li>
                
                </ul>
                </div>
            </div>
  </div>



<div class="list-group">
    <a class="list-group-item active"> 
    <h2 class="list-group-item-heading">Formulario de Modificacion </h2>
    <p class="list-group-item-text">Edita los campos para actualizar la ficha.</p>
    </a>
</div>
<div class="well">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-content">     

<div class="lead">
<form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post" enctype="multipart/form-data">
    <table>
        <tr>
            <td class="col-xs-11" class="align-left">
                <!-- Nombre-->
                <div <?php echo (!empty($Errnombre)) ? 'error: ' : ''; ?> >
                    <label>Nombre</label>
                    <input type="text" name="nombre" value="<?php echo $nombreAnterior; ?>">
                    <?php echo $Errnombre; ?>
                </div>
            </td>
            <!-- Fotografía -->
            <td class="col-xs-11" class="align-right">
                <label>Fotografía</label><br>
                <img src='<?php echo "../imagenes/fotos/" . $luchador->getimagen() ?>' class='rounded' class='img-thumbnail' width='150' height='auto'>
            </td>
        </tr>
    </table>
    <!-- raza -->
    <div class="form-group  <?php echo (!empty($Errraza)) ? 'error: ' : ''; ?>">
        <label>Raza</label>
        <input type="radio" name="raza" value="Saiyan" <?php echo (strstr($raza, 'Saiyan')) ? 'checked' : ''; ?>>Saiyan</input>
        <input type="radio" name="raza" value="Terricola" <?php echo (strstr($raza, 'Terricola')) ? 'checked' : ''; ?>>Terricola</input>
        <input type="radio" name="raza" value="Demonio" <?php echo (strstr($raza, 'Demonio')) ? 'checked' : ''; ?>>Demonio</input>
        <input type="radio" name="raza" value="Androide" <?php echo (strstr($raza, 'Androide')) ? 'checked' : ''; ?>>Androide</input>
        <input type="radio" name="raza" value="Angel" <?php echo (strstr($raza, 'Angel')) ? 'checked' : ''; ?>>Angel</input>
        <input type="radio" name="raza" value="Dragon" <?php echo (strstr($raza,'Dragon')) ? 'checked' : ''; ?>>Dragon</input>
        <input type="radio" name="raza" value="Namekiano" <?php echo (strstr($raza, 'namekiano')) ? 'checked' : ''; ?>>Namekiano</input>
        <input type="radio" name="raza" value="3 Ojos" <?php echo (strstr($raza, '3 Ojos')) ? 'checked' : ''; ?>>3 Ojos</input><br>
        <span class="help-block"><?php echo $Errraza; ?></span> 
    </div>
    <!-- ki -->
    <div class="form-group <?php echo (!empty($Errki)) ? 'error: ' : ''; ?>">
        <label>KI</label>
        <input type="text" required name="ki" pattern="([1-9][0-9]{2})" minlength="3" maxlength="3"  title="Inserte un numero desde el 100 hasta el 999" value="<?php echo $ki; ?>">
        <?php echo $Errki; ?>
    </div>
    <!-- transformacion-->
    <div class="form-group">
        <label>Transformacion</label>
        <select name="transformacion">
            <option value="SI" <?php echo (strstr($transformacion, 'SI')) ? 'selected' : ''; ?>>SI</option>
            <option value="NO" <?php echo (strstr($transformacion, 'NO')) ? 'selected' : ''; ?>>NO</option>
        </select>
    </div>
    <!-- ataque -->
    <div class="form-group <?php echo (!empty($Errataque)) ? 'error: ' : ''; ?>"s>
        <label>Ataque</label>
        <input type="checkbox" name="ataque[]" value="Fisico" <?php echo (strstr($ataque, 'Fisico')) ? 'checked' : ''; ?>>Fisico</input>
        <input type="checkbox" name="ataque[]" value="Onda" <?php echo (strstr($ataque, 'Onda de Energia')) ? 'checked' : ''; ?>>Onda de Energia</input>
        <input type="checkbox" name="ataque[]" value="Ultra" <?php echo (strstr($ataque, 'Ultra Instinto')) ? 'checked' : ''; ?>>Ultra Instinto</input>
        <input type="checkbox" name="ataque[]" value="Ninguno" <?php echo (strstr($ataque, 'Ninguno')) ? 'checked' : ''; ?>>Ninguno</input>
        <span class="help-block"><?php echo $Errataque; ?></span> 
    </div>
    <!-- planeta -->
    <div <?php echo (!empty($Errplaneta)) ? 'error: ' : ''; ?>>
        <label>Planeta</label>
        <input type="text" required name="planeta" value="<?php echo $planeta; ?>" pattern="([A-zÀ-ž]){3}([0-9]){2}" title="formato LLLNN donde L es una letra y N un numero">
        <?php echo $Errplaneta; ?>
    </div>
    <!-- Password -->
    <div <?php echo (!empty($Errpassword)) ? 'error: ' : ''; ?>">
        <label>Password</label>
        <input type="password" required name="password" value="*****" >
        <?php echo $Errpasssword; ?>
    </div>
    <!-- Fecha-->
    <div <?php echo (!empty($Errfecha)) ? 'error: ' : ''; ?>>
        <label>Fecha de Matriculación</label>
        <input type="date" required name="fecha" value="<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $fecha))); ?>"></input>
        <div>
            <?php echo $Errfecha; ?>
        </div>
        <!-- Foto-->
        <div <?php echo (!empty($Errimagen)) ? 'error: ' : ''; ?>>
            <label>Fotografía</label>
            <input type="file" name="imagen" id="imagen" accept="image/jpeg">
            <?php echo $Errimagen; ?>
        </div>
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="passwordAnterior" value="<?php echo encode($passwordAnterior); ?>" />
        <input type="hidden" name="imagenAnterior" value="<?php echo $imagenAnterior; ?>" />
        <input type="hidden" name="nombreAnterior" value="<?php echo $nombreAnterior; ?>" />
        </div>
        </div> 
            </div>
                </div>
                    </div>
                        </div> 
                            </div>
                                                          
    <span class="list-group-item text-center">
     <a onclick="history.back()" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span> Volver</a>
     <button type="submit" name= "aceptar" value="aceptar" class=" btn btn-success" ><span class="glyphicon glyphicon-ok"></span><strong> Modificar</h5></strong>  </button>


     </span>
    </div>
    </div>  
</form>

<br>
<br>
<br>
</section>

<?php require_once VIEW_PATH . "footer.php" ?>
