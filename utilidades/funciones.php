<?php
function alerta($texto, $ruta=null)
{
    echo "<script>";
    echo "alert('" . $texto . "');";
    if($ruta!=null)
        echo "window.location= '" . $ruta . "'";
    echo "</script>";
}


// filtrado de datos formulario
function filtrado($datos) {
    $datos = trim($datos); // Elimina espacios antes y después de los datos
    $datos = stripslashes($datos); // Elimina backslashes \
    $datos = htmlspecialchars($datos); // Traduce caracteres especiales en entidades HTML
    return $datos;
}

// Codifica en base64
function encode($str){
    return urlencode(base64_encode($str));
}
//Decodifica en base64
function decode($str){
    return base64_decode(urldecode($str));
}

?>

