<?php

require_once '../Config/Config.php';

class Conexion {

    public static $pdo; // Una referencia a un objeto de tipo PDO (PHP Data Object)
    private static $conexion;

    /**
     * La función construct es privada para evitar que el objeto pueda ser creado mediante new.
     * Cuando este método se llama, crea una conexión a una base de datos.
     */
    private function __construct() {
        
    }

    /**
     * Es posible que un script envié varios mensajes getInstance(...) a un objeto de tipo Conexion,
     * sinembargo siempre se retornará la misma instancia de Conexión, garantizando así la
     * implementacion del Patrón Singleton
     * @param <type> $driver El tipo de driver: postgres, mysql, etc.
     * @param <type> $servidor El host: localhost o cualquier IP válida
     * @param <type> $usuario El usuario que tiene privilegios de acceso a la base de datos
     * @param <type> $clave La clave del usuario
     * @return <type> Una instancia de tipo Conexion
     */
    public static function getInstance() {
        // la siguiente condición garantiza que sólo se crea una instancia de esta clase si _instancia no es de tipo Conexion
        if (!isset(self::$conexion)) {
            self::$conexion = new self();  // llamado al constructor
            self::$pdo = new PDO('pgsql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . DB_CHAR));
        }
        return self::$conexion;
    }

    /**
     * Se sobreescribe este 'método mágico' para evitar que se creen clones de esta clase
     */
    private function __clone() {
        
    }

    public function getPDO() {
        return self::$pdo;
    }

    /**
     * Recibe el nombre de un operador de búsqueda jqGrid y devuelve un operador PostgreSQL
     * @param <type> $operador El nombre del operador de búsqueda como lo define jqGrid
     */
    public function getOperador($operador) {
        $operadores = array(
            'eq' => "=", // Igual
            'ne' => "<>", // No igual
            'lt' => "<", // Menor
            'le' => "<=", // Menor o igual
            'gt' => ">", // Mayor
            'ge' => ">=", // Mayor o igual
            'bw' => "LIKE", // Comienza con.  OJO EL LIKE en Postgres es sensible a uso de mayúsculas
            'bn' => "NOT LIKE", // No comienza con
            'in' => "IN", // En
            'ni' => "NOT IN", // No esta en
            'ew' => "LIKE", // Termina con
            'en' => "NOT LIKE", // No termina con
            'cn' => "LIKE", // Contiene
            'nc' => "NOT LIKE"); // No contiene
        return $operadores[$operador];
    }

    /**
     * Devuelve una cláusula WHERE simple construida a partir de los datos
     * suministrados como argumentos.
     * @param <type> $buscar Si false el WHERE no se crea y se retorna una cadena vacía
     * IMPORTANTE:   Verifique el uso de los operadores, es solo un ejemplo.
     *               Tenga en cuenta que también se pueden hacer búsquedas avanzadas
     * Ver search_adv.php en: http://www.trirand.com/blog/jqgrid/downloads/jqgrid_demo36.zip
     *  DEBE RECIBIRSE UN PARAMETRO ADICIONAL PARA VERIFICAR EL TIPO DE DATOS
      CASO COMO EL DE TELEFONOS FALLA EN ESTA VERSIÓN
     */
    public function getWhere($pDatos) {
        $where = "";
        if ($pDatos['_search'] == 'true') {
            $dato = $pDatos['searchField'];
            $operadorBusqueda = $pDatos['searchOper'];
            $valor = $pDatos['searchString'];
            $operador = $this->getOperador($operadorBusqueda);

            switch ($operadorBusqueda) {
                case 'eq': //Igual
                case 'ne': //No igual
                case 'lt': // Menor
                case 'le': // Menor o igual
                case 'gt': // Mayor
                case 'ge': // Mayor o igual
                    if (is_string($valor)) {  // Verificar si funciona con fechas
                        $where = "WHERE $dato $operador '$valor'";
                    } else {
                        $where = "WHERE $dato $operador $valor";
                    }
                    break;
                case 'bw': // Comienza con
                    if (is_string($valor)) {
                        $where = "WHERE $dato $operador '$valor%'";
                    }
                    break;
                case 'bn': // No comienza con
                    if (is_string($valor)) {
                        $where = "WHERE $dato $operador '$valor%'";
                    }
                    break;
                /*   case 'in': // En
                  case 'ni': // No esta en */
                case 'ew': // Termina con
                    if (is_string($valor)) {
                        $where = "WHERE $dato $operador '%$valor'";
                    }
                    break;
                case 'en': // No termina con
                    if (is_string($valor)) {
                        $where = "WHERE $dato $operador '%$valor'";
                    }
                    break;
                case 'cn': // Contiene
                    if (is_string($valor)) {
                        $where = "WHERE $dato $operador '%$valor%'";
                    }
                    break;
                case 'nc': // No contiene
                    if (is_string($valor)) {
                        $where = "WHERE $dato $operador '%$valor%'";
                    }
            }
        }
        return $where;
    }

    public function getWhere2($pDatos) {
        $where = "";
        if ($pDatos['_search'] == 'true') {  // ☺
            $dato = $pDatos['searchField'];
            $operadorBusqueda = $pDatos['searchOper'];
            $valor = $pDatos['searchString'];
            $operador = $this->getOperador($operadorBusqueda);

            switch ($operadorBusqueda) {
                case 'eq': //Igual
                case 'ne': //No igual
                case 'lt': // Menor
                case 'le': // Menor o igual
                case 'gt': // Mayor
                case 'ge': // Mayor o igual
                    if (is_string($valor)) {  // Verificar si funciona con fechas
                        $where = "AND $dato $operador '$valor'";
                    } else {
                        $where = "AND $dato $operador $valor";
                    }
                    break;
                case 'bw': // Comienza con
                    if (is_string($valor)) {
                        $where = "AND $dato $operador '$valor%'";
                    }
                    break;
                case 'bn': // No comienza con
                    if (is_string($valor)) {
                        $where = "AND $dato $operador '$valor%'";
                    }
                    break;
                /*   case 'in': // En
                  case 'ni': // No esta en */
                case 'ew': // Termina con
                    if (is_string($valor)) {
                        $where = "AND $dato $operador '%$valor'";
                    }
                    break;
                case 'en': // No termina con
                    if (is_string($valor)) {
                        $where = "AND $dato $operador '%$valor'";
                    }
                    break;
                case 'cn': // Contiene
                    if (is_string($valor)) {
                        $where = "AND $dato $operador '%$valor%'";
                    }
                    break;
                case 'nc': // No contiene
                    if (is_string($valor)) {
                        $where = "AND $dato $operador '%$valor%'";
                    }
            }
        }
        return $where;
    }

}

?>
