<?php
/**
 *
 * @author Andres Diaz
 */
interface Persistible {

    function add($pDatos);

    function edit($pDatos);

    function del($pDatos);

    function select($pDatos);

    function getList();
}
?>
