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

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = decode($_GET["id"]);
    $controlador = ControladorLuchador::getControlador();
    $luchador = $controlador->buscarLuchadorid($id);
    if (is_null($luchador)) {
        header("location: error.php");
        exit();
    }
}


if (isset($_POST["id"]) && !empty($_POST["id"])) {
    $controlador = ControladorLuchador::getControlador();
    $luchador = $controlador->buscarLuchadorid($_POST["id"]);
    if ($controlador->borrarLuchador($_POST["id"])) {

        $controlador = ControladorImagen::getControlador();
        if ($controlador->eliminarImagen($luchador->getimagen())) {
            header("location: ../index.php");
            exit();
        } else {
            header("location: error.php");
            exit();
        }
    } else {
        header("location: error.php");
        exit();
    }
}
?>
<?php require_once VIEW_PATH . "navbar.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>

<style type="text/css">
.banner-section{background-image:url("../imagenes/delete.jpg"); background-size:1500px 350px ; height: 450px; left: 0; position: absolute; top: 0; background-position:0; background-repeat: no-repeat; }
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
            <div id='cabecera'> <h1 class="display-1 text-center">Borrar Luchador</h1></div>
            <div id="menu">    
            <ul class="list-inline text-center">
               
                    <li>Jose F |</li>
                    <li>CRUD Dragon Ball  |</li>
                    <li>Borrar</li>
                
                </ul>
                </div>
            </div>

    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                <br>
<br>
                <div class="lead">
                <div class="well">
                
<table>

    <tr>
    <td class="col-xs-11" class="align-left">
            <label><h2>Nombre</h2></label>
            <p class="form-control-static"><div class="lead"><?php echo $luchador->getnombre(); ?></p>
        
   </td>
    <td class="col-xs-11" class="align-right">
            <label><h3>Fotografía</h3></label><br>
            <img src='<?php echo "../imagenes/fotos/" . $luchador->getimagen() ?>' class='rounded' class='img-thumbnail' width='160px' height='auto'>
    </td>
</tr>
</table>
<div class="form-group">
<label><h2>Raza</h2></label>
<p class="form-control-static"><?php echo $luchador->getraza(); ?></p>
  </div>
<label><h2>KI</h2></label>
<div class="form-group">
<p class="form-control-static"><?php echo $luchador->getki(); ?></p>
</div>
<div class="form-group">
<label><h2>Transformación</h2></label>
<p class="form-control-static"><?php echo $luchador->gettransformacion(); ?></p>
</div>
<div class="form-group">
<label><h2>Ataque</h2></label>
<p class="form-control-static"><?php echo $luchador->getataque(); ?></p>
</div>
<div class="form-group">
<label><h2>Planeta</h2></label>
<p class="form-control-static"><?php echo $luchador->getplaneta(); ?></p>
</div>
<div class="form-group">
<label><h2>Fecha de inscripción</h2></label>
<p class="form-control-static"><?php echo $luchador->getfecha(); ?></p>
</div>
  <span class="list-group-item  text-center list-group-item-danger">
  <h1>Borrar Luchador</h1>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div>
        <input type="hidden" name="id" value="<?php echo trim($id); ?>" />
        <p>¿Está seguro que deseas borrar este Luchador/a?</p><br>
        <p>
        <a href='../index.php' class="btn btn-primary"><span class="glyphicon glyphicon-chevron-left"></span> Volver</a>
        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Seguro que desea borrar este valioso luchador/a?')"> Borrar</button>
        </p>
    </div>
</form>
     </span>  
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</body>
</html>

<?php require_once VIEW_PATH ."footer.php" ?>