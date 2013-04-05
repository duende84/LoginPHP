<?php

header('Content-Type: text/html; charset=UTF-8');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  // disable IE caching
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

error_reporting(E_ALL);
$errores = '';
try {
    spl_autoload_register('__autoload');
    $conexion = Conexion::getInstance(/** *************** ver Conexion *********************** */);
    // Debe validarse para los casos en los que los archivos de clases no existan
    if (isset($_REQUEST['clase'])) {
        $clase = $_REQUEST['clase'];
        if (isset($_REQUEST['oper'])) {
            $metodo = $_REQUEST["oper"];
            $argumentos = $_REQUEST;
            $obj = new $clase($conexion);
            $obj->$metodo($argumentos);
        } else {
            throw new Exception('El controlador no ha recibido un mensaje válido.');
        }
    } else {
        throw new Exception('El controlador no sabe a quien enviar el mensaje.');
    }
} catch (Exception $e) {
    echo json_encode(array("ok" => 0, "mensaje" => $e->getMessage()));
}

/**
 * Intenta cargar aquellas clases que no se incluyen explícitamente.
 * @param <type> $nombreClase el nombre de la clase que se intentará cargar
 * desde la ruta ../Modelo/
 * IMPORTANTE: include_once no lanza excepciones
 */
function __autoload($nombreClase) {
    if ($nombreClase == "Conexion") {
        $nombreClase = "$nombreClase.php";
    } else {
        $nombreClase = "../Modelo/$nombreClase.php";
    }

    if (file_exists($nombreClase)) {
        include_once($nombreClase);
    } else {
        throw new Exception("No existe la clase $nombreClase");
    }
}

?>
