<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/dragonball/dirs.php";
require_once CONTROLLER_PATH . "ControladorBD.php";
require_once CONTROLLER_PATH . "ControladorAcceso.php";
require_once UTILITY_PATH . "funciones.php";

$controlador = ControladorAcceso::getControlador();
$controlador->salirSesion();

require_once VIEW_PATH . "navbar.php"; 

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email=filtrado($_POST['email']);
    $pass=filtrado($_POST['password']);
    $controlador = ControladorAcceso::getControlador();
    $controlador->procesarIdentificacion($email,$pass);
}
?>
<style type="text/css">

.banner-section{background-image:url("../imagenes/login.jpg"); background-size:1470px 600px ; height: 320px; left: 0; position: absolute; top: 0; background-position:0; background-repeat: no-repeat; }
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
            <div id='cabecera'> <h1 class="display-1 text-center">Login</h1> </div>
            <div id="menu">    
            <ul class="list-inline text-center">
               
                    <li>Jose F |</li>
                    <li>CRUD Dragon Ball |</li>
                    <li>Login</li>
                
                </ul>
                </div>
            </div>
  </div>
<?php
?>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <!-- Nombre-->
    <div class="well text-center">
    <p class="lead"> 
    
        <label>Email:</label>
        <input type="email" required name="email" value="admin@admin.com">
 
    <!-- Contraseña -->
    
        <label>Contraseña:</label>
        <input type="password" required name="password" value="admin">
   <br><br>
   <button type="submit"  value="aceptar" class=" btn btn-success" ><span class="glyphicon glyphicon-user"></span><strong> Acceder</strong></button>
   <a href="../index.php" class="btn btn-info"><span class="glyphicon glyphicon-th-list"></span> Volver</a>
  </p>
  </div>
</form>
