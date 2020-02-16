<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/dragonball/dirs.php";
require_once CONTROLLER_PATH . "ControladorLuchador.php";
require_once CONTROLLER_PATH . "Paginador.php";
require_once UTILITY_PATH . "funciones.php";

session_start();


error_reporting(E_ALL ^ E_NOTICE);
  if(isset($_COOKIE['CONTADOR']))
  { 
    // Caduca en un día
    setcookie('CONTADOR', $_COOKIE['CONTADOR'] + 1, time() + 24 * 60 * 60); // un día
    $contador = 'Número de visitas hoy: ' . $_COOKIE['CONTADOR']; 
  } 
  else 
  { 
    // Caduca en un día
    setcookie('CONTADOR', 1, time() + 24 * 60 * 60); 
    $cotador = 'Número de visitas hoy: 1'; 
  } 
  if(isset($_COOKIE['ACCESO']))
  { 
    // Caduca en un día
    setcookie('ACCESO', date("d/m/Y  H:i:s"), time() + 3 * 24 * 60 * 60); // 3 días
    $acceso = '<br>Último acceso: ' . $_COOKIE['ACCESO']; 
  } 
  else 
  { 
    // Caduca en un día
    setcookie('ACCESO', date("d/m/Y  H:i:s"), time() + 3 * 24 * 60 * 60); 
    $acceso = '<br>Último acceso: '. date("d/m/Y  H:i:s"); 
  } 
?>
<!------ Include the above in your HEAD tag ---------->
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>PDO-DB</title>
  <link rel="icon" type="image/png" href="/imagenes/mifavicon.png"/>

<style type="text/css">
.navbar{margin-bottom:0;}
section{width:100%; float:left;}
/* .banner-section{background-image:url("https://static.pexels.com/photos/373912/pexels-photo-373912.jpeg"); background-size:cover; height: 380px; left: 0; position: absolute; top: 0; background-position:0;} */
.post-title-block{padding:100px 0;}
.post-title-block h1 {color: #fff; font-size: 85px; font-weight: bold; text-transform: capitalize;}
.post-title-block li{font-size:20px; color: #fff;}
.image-block{float:left; width:100%; margin-bottom:10px;}
.footer-link{float:left; width:100%; background:#222222; text-align:center; padding:30px;}
.footer-link a{color:#A9FD00; font-size:18px; text-transform:uppercase;}
</style>
<link rel="shortcut icon" type="image/x-icon" href="/dragonball/imagenes/mifavicon.ico" />
 <!-- Static navbar -->
 <nav class="navbar navbar-inverse  navbar-static-top">
  <div class="container-fluid">
  <div class="navbar-header">
          <a class="navbar-brand" href="#">CRUD-DB</a>
        </div>
    <ul class="nav navbar-nav">
    <li class="active"><a href="/dragonball/index.php">Menu</a></li> 
    </ul>
    <ul class="nav navbar-nav navbar-right">
          <?php if(!isset($_SESSION['email'])){?>
            <li class="active"><a href="#"><span class="glyphicon glyphicon-user"></span> Registrarse<span class="sr-only">(current)</span></a></li>
            <li><a href="/dragonball/vistas/login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
            <?php }else{ ?>
            <li class="active"><a href="#"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['email'] ;?></a></li>
            <li><a href="/dragonball/vistas/login.php"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
          <?php } ?>  
    </ul>
    <?php
    $url=($_SERVER["REQUEST_URI"]);
     if(strstr($url,'index.php')){?> 
    <form class="navbar-form navbar-right" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <div class="form-group">
        <input type="text" id="buscar" name="luchador" class="form-control" placeholder="Buscar luchador">
      </div>
      <button type="submit" class="btn btn-default">Buscar</button>
    </form>
    <?php }?>
  </div>
</nav>
<section class="banner-section">
    </section>
