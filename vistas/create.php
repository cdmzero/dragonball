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

// Variables a procesar
$nombre = $raza = $ki = $transformacion = $ataque =  $planeta = $password = $fecha = $imagen = "";
$Valnombre = $Valraza = $Valki = $Valtransformacion = $Valataque = $Valplaneta = $Valpassword = $Valfecha  = "";
$Errnombre = $Errraza = $Errki = $Errtransformacion = $Errataque = $Errplaneta = $Errpassword = $Errfecha =  $Errimagen = "";
$errores = [];



if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["aceptar"]) {

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
    
    $controlador = ControladorDragon::getControlador();
    $luchador = $controlador->buscarLuchador($nombre);
    if (isset($luchador)) {
        $Errnombre = "Ya existe un Luchador con este nombre en la Base de Datos";
        $errores[]= $Errnombre ;
    } else {
        $nombre = $Valnombre;
    }

    // Procesamos raza
    $Valraza = filtrado($_POST["raza"]);
    if (empty($Valraza)) {
        $Errraza = "Debe elegir al menos una raza";
        $errores[]= $Errraza ;
    } else {
        $raza = $Valraza;
    }

    // KI
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

    //Transformacion
    $Valtransformacion = filtrado($_POST["transformacion"]);
    if (empty($Valtransformacion)) {
        $Errtransformacion = "Debe elegir al menos una transformacion";
        $errores[] = $Errtransformacion;
    } else {
        $transformacion = $Valtransformacion;
    }

    // Ataque
    $Valataque=$_POST["ataque"];
    if (empty($Valataque)) {
        $Errataque = "Debe elegir al menos un ataque";
        $errores[] = $Errataque;
    } else {
        $ataque = implode(", ",$_POST["ataque"]);
    }

    // Planeta
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

    //Password
    $Valpassword = filtrado($_POST["password"]);
    if (empty($Valpassword) || strlen($Valpassword) < 5) {
        $Errpassword = "Por favor introduzca password válido y que sea mayor que 5 caracteres.";
        $errores[] = $Errpassword ;
    } else {
        $password = hash('sha256', $Valpassword);
    }

    //Fecha
    $fecha = date("d-m-Y", strtotime(filtrado($_POST["fecha"])));
    $hoy = date("d-m-Y", time());

    // Comparamos fechas
    $fecha_mat = new DateTime($fecha);
    $fecha_hoy = new DateTime($hoy);
    $interval = $fecha_hoy->diff($fecha_mat);

    if ($interval->format('%R%a días') > 0) {
        $Errfecha = "La fecha no puede ser superior a la fecha actual";
        $errores[] = $Errfecha;
    } else {
        $fecha = date("d/m/Y", strtotime($fecha));
    }

    // Procesamos la foto
    $propiedades = explode("/", $_FILES['imagen']['type']);
    $extension = $propiedades[1];
    $tam_max = 1000000; // 1 Mb
    $tam = $_FILES['imagen']['size'];
    $mod = true; // para modificar

    // Si no coicide la extensión
    if ($extension != "jpg" && $extension != "jpeg") {
        $mod = false;
        $imagenErr = "Formato debe ser jpg/jpeg";
    }
    // si no tiene el tamaño
    if ($tam > $tam_max) {
        $mod = false;
        $Errimagen = "Tamaño superior al limite de 1MB";
        $errores[]=  $Errimagen;
    }

    if ($mod) {
        //guardar imagen
        $imagen = md5($_FILES['imagen']['tmp_name'] . $_FILES['imagen']['name'] . time()) . "." . $extension;
        $controlador = ControladorImagen::getControlador();
        if (!$controlador->salvarImagen($imagen)) {
            $Errimagen = "Error al procesar la imagen y subirla al servidor";
            $errores[] =  $Errimagen;
        }
    }

    if (empty($errores)) {
        // creamos el controlador de Luchadores
        $controlador = ControladorLuchador::getControlador();
        $estado = $controlador->almacenarLuchador($nombre, $raza, $ki, $transformacion, $ataque, $planeta, $password, $fecha, $imagen);
        if ($estado) {
            header("location: ../index.php");
            exit();
        } else {
            header("location: error.php");
            exit();
        }
    } else {
        alerta("Hay errores al procesar el formulario revise los errores");
    }
  

}
?>

<?php require_once VIEW_PATH . "navbar.php"; ?>
<head>
<style type="text/css">

.banner-section{background-image:url("../imagenes/crear.jpg"); background-size:1500px 500px ; height: 380px; left: 0; position: absolute; top: 0; background-position:0; background-repeat: no-repeat; }
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
            <div id='cabecera'> <h1 class="display-1 text-center">Nuevo Luchador</h1> </div>
            <div id="menu">    
            <ul class="list-inline text-center">
               
                    <li>Jose F |</li>
                    <li>CRUD Dragon Ball  |</li>
                    <li>Crear</li>
                
                </ul>
                </div>
            </div>
  </div>

<div class="list-group">
    <a class="list-group-item active"> 
    <h2 class="list-group-item-heading">Formulario </h2>
    <p class="list-group-item-text">Todos los campos son requeridos para completarse la creacion.</p>
    </a>
</div>
<div class="well">
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel-content">     

<div class="lead">
<!-- Formulario-->

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <!-- Nombre-->
    <div class="form-group <?php echo (!empty($Errnombre)) ? 'error: ' : ''; ?>">
    
        <label>Nombre</label>
        <input type="text" required name="nombre" pattern="([^\s][A-zÀ-ž\s]+)" title="El nombre no puede contener números" value="<?php echo $Valnombre; ?>">
        <span class="help-block"><?php echo $Errnombre;?></span> 
    </div>
    <!-- raza -->
    <div class="form-group  <?php echo (!empty($Errraza)) ? 'error: ' : ''; ?>">
        <label>Raza</label>
        <input type="radio" name="raza" value="Saiyan" <?php echo (strstr($Valraza, 'Saiyan')) ? 'checked' : ''; ?>>Saiyan</input>
        <input type="radio" name="raza" value="Terricola" <?php echo (strstr($Valraza, 'Terricola')) ? 'checked' : ''; ?>>Terricola</input>
        <input type="radio" name="raza" value="Demonio" <?php echo (strstr($Valraza, 'Demonio')) ? 'checked' : ''; ?>>Demonio</input>
        <input type="radio" name="raza" value="Androide" <?php echo (strstr($Valraza, 'Androide')) ? 'checked' : ''; ?>>Androide</input>
        <input type="radio" name="raza" value="Angel" <?php echo (strstr($Valraza, 'Angel')) ? 'checked' : ''; ?>>Angel</input>
        <input type="radio" name="raza" value="Dragon" <?php echo (strstr($Valraza,'Dragon')) ? 'checked' : ''; ?>>Dragon</input>
        <input type="radio" name="raza" value="Namekiano" <?php echo (strstr($Valraza, 'namekiano')) ? 'checked' : ''; ?>>Namekiano</input>
        <input type="radio" name="raza" value="3 Ojos" <?php echo (strstr($Valraza, '3 Ojos')) ? 'checked' : ''; ?>>3 Ojos</input><br>
        <span class="help-block"><?php echo $Errraza; ?></span> 
    </div>
    <!-- ki -->
    <div class="form-group <?php echo (!empty($Errki)) ? 'error: ' : ''; ?>">
        <label>KI</label>
        <input type="text" required name="ki" pattern="([1-9][0-9]{2})" minlength="3" maxlength="3" title="Inserte un numero  del 100 al 999" value="<?php echo $Valki; ?>">
        <span class="help-block"><?php  echo $Errki; ?></span> 
    </div>
    <!-- transformacion-->
    <div class="form-group <?php echo (!empty($Errtransformacion)) ? 'error: ' : ''; ?>">
        <label>Transformacion</label>
        <select name="transformacion">
            <option value="SI" <?php echo (strstr($Valtransformacion, 'SI')) ? 'selected' : ''; ?>>SI</option>
            <option value="NO" <?php echo (strstr($Valtransformacion, 'NO')) ? 'selected' : ''; ?>>NO</option>
        </select>
        <span class="help-block"><?php  echo $Errtransformacion; ?></span> 
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
    <div class="form-group<?php echo (!empty($Errplaneta)) ? 'error: ' : ''; ?>">
        <label>Planeta</label>
        <input type="text" required name="planeta" value="<?php echo $Valplaneta; ?>" pattern="([A-zÀ-ž]){3}([0-9]){2}" 
            title="formato LLLNN donde L es una letra y N un numero">
        <span class="help-block"><?php echo $Errplaneta; ?></span> 
    </div>
    <!-- contraseña-->
    <div class="form-group<?php echo (!empty($passwordErr)) ? 'error: ' : ''; ?>">
        <label>Password</label>
        <input type="password" required name="password" class="form-control" minlength="5" maxlength="10">
        <span class="help-block"><?php echo $passwordErr; ?></span> 
    </div>
    <!-- fecha-->
    <div class="form-group <?php echo (!empty($fechaErr)) ? 'error: ' : ''; ?>">
        <label>Fecha</label>
        <input type="date" required name="fecha" value="<?php echo date('Y-m-d', strtotime(str_replace('/', '-', $fecha))); ?>"></input>
        <span class="help-block"><?php echo $fechaErr; ?></span> 
    <!-- Foto-->
    <div class="form-group <?php echo (!empty($imagenErr)) ? 'error: ' : ''; ?>">
        <label>Fotografía</label>
        <input type="file" required name="imagen" id="imagen" accept="image/jpeg">
        <span class="help-block"><?php echo $imagenErr; ?></span> 
    </div>
        </div> 
            </div>
                </div>
                    </div>
                        </div> 
                            </div>
                                </div>                             
    <span class="list-group-item text-center">
     <a onclick="history.back()" class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span> Volver</a>
     <button type="reset" value="reset" class="btn btn-info"> <span class="glyphicon glyphicon-repeat"></span>  Limpiar</button> 
     <button type="submit" name= "aceptar" value="aceptar" class=" btn btn-success" ><span class="glyphicon glyphicon-ok"></span><strong> Crear</h5></strong>  </button>


     </span>
    </div>
</form>
<br>
<br>
<br>
</section>

<?php require_once VIEW_PATH . "footer.php" ?>

