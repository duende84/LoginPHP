<?php

require_once '../Config/Config.php';

class Login {

    private $conexion;
    private $pdo;

    function __construct($conexion) {
        $this->conexion = $conexion;
        $this->pdo = $this->conexion->getPDO();
    }

    function login($pDatos) {
        extract($pDatos);
        $sql = "SELECT * FROM usuario WHERE nombre_usuarios='$username' and pass = '$hash'";
        $ok = $this->pdo->query($sql);
        foreach ($ok as $row) {
            session_start();
            $_SESSION['estado_sesion'] = true;
            $_SESSION['id_usuario'] = $row['id'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['nombre_usuario'] = $row['nombre_usuarios'];
            echo json_encode("exito");
        }
    }

    /* Este metodo cierra la session del usuario, esto se hace para que la
     * session no quede activa y asi poder iniciar por url.
     */

    function cerrar($pDatos) {
        extract($pDatos);
        session_start();
        $_SESSION['estado_sesion'] = false;
    }

}
?>
