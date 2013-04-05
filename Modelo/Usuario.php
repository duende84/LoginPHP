<?php

require_once '../Config/Config.php';

/**
 * Description of Usuario
 *
 * @author Andres
 */
class Usuario implements Persistible {

    private $conexion;
    private $pdo;    

    function __construct($conexion) {
        $this->conexion = $conexion;
        $this->pdo = $this->conexion->getPDO();        
    }

    public function add($pDatos) {
        extract($pDatos);
        $sql = "INSERT INTO usuario (id, nombre, pass, nombre_usuario)
            VALUES (NULL, :nombre, :pass, :nombre_usuario);";
        $ok = $this->pdo->prepare($sql)
                ->execute(
                array(
                    ':nombre' => $nombre,
                    ':pass' => sha1($pass),
                    ':nombre_usuario' => $nombre_usuario
                ));
        echo json_encode($ok ? array('ok' => TRUE, "mensaje" => "Los datos fueron insertados") : array('ok' => FALSE, "mensaje" => "Los datos no fueron insertados"));
    }

    public function del($pDatos) {
        $datos = "(" . $pDatos['id'] . ")";
        $sql = ("DELETE from usuario WHERE id IN $datos;");
        try {
            $ok = $this->pdo->prepare($sql)->execute();
            echo json_encode($ok ? array('ok' => TRUE, "mensaje" => "Los datos fueron eliminados") : array('ok' => FALSE, "mensaje" => "No se ha eliminado ningun dato"));
        } catch (Exception $e) {
            echo json_encode(array("mensaje" => $e->getMessage()));
        }
    }

    public function edit($pDatos) {
        extract($pDatos);
        $sql = "UPDATE usuario SET
            nombre = :nombre,            
            pass = :pass,            
            nombre_usuario = :nombre_usuario
            WHERE id = :id;";
        $ok = $this->pdo->prepare($sql)
                ->execute(
                array(
                    ':nombre' => $nombre,
                    ':pass' => $hash,
                    ':nombre_usuario' => $nombre_usuario,
                    ':id' => $id_usuario
                ));
        //echo json_encode($ok ? array('ok' => TRUE, "mensaje" => "Los datos fueron editados") : array('ok' => FALSE, "mensaje" => "Los datos no fueron editados"));
        if ($ok == TRUE) {
            echo json_encode("Se ha editado correctamente tu perfil");
        } else {
            echo json_encode("Error");
        }
    }

    public function getList() {
        $filas['-1'] = 'Seleccione un usuario';
        $sql = "SELECT nombre, id FROM usuario";
        foreach ($this->pdo->query($sql) as $row) {
            $filas[$row['id']] = $row['nombre'];
        }
        echo json_encode($filas);
    }

    public function select($pDatos) {
        $where = $this->conexion->getWhere($pDatos); // Se construye la clausula WHERE
        extract($pDatos);
        $sql = $this->pdo->query("SELECT id FROM usuarios");
        $count = $sql->rowCount();
        // Calcula el total de páginas por consulta
        if ($count > 0) {
            $total_pages = ceil($count / $rows);
        } else {
            $total_pages = 0;
        }

        // Si por alguna razón página solicitada es mayor que total de páginas
        // Establecer a página solicitada total paginas  (¿por qué no al contrario?)
        if ($page > $total_pages)
            $page = $total_pages;

        // Calcular la posición de la fila inicial
        $start = $rows * $page - $rows;
        //  Si por alguna razón la posición inicial es negativo ponerlo a cero
        // Caso típico es que el usuario escriba cero para la página solicitada
        if ($start < 0)
            $start = 0;

        $sql = "
                SELECT
                u.id,                
                u.nombre,
                u.pass,
                u.email,
                u.fk_tipo_usuario,
                tu.nombre as tipo,
                FROM usuarios u
                INNER JOIN tipo_usuario tu ON tu.id = u.fk_tipo_usuario
                $where ORDER BY $sidx $sord LIMIT $start, $rows;
               ";

        $result = $this->pdo->query($sql);

        // Se construye el JSON
        $response->total = $total_pages;
        $response->page = $page;
        $response->records = $count;
        $i = 0;
        /////////////////////////////////////////////////////////////////////////////////////////
        foreach ($result as $row) {
            $response->rows[$i]['id'] = $row['id'];
            $response->rows[$i]['cell'] = array(
                $row['id'],
                $row['nombre'],
                $row['email'],
                $row['pass'],
                $row['tipo']);
            $i++;
        }
        echo json_encode($response);
    }
}

?>
