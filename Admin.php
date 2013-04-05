<?php
session_start();
if (($_SESSION['estado_sesion'] == false))
    header("Location: index.html");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
           echo("Bienvenido Administrador");
        ?>
    </body>
</html>
