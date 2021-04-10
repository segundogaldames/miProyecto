<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('class/conexion.php');

if (isset($_GET['id'])) {
    
    $id = (int) $_GET['id'];

    $res = $mbd->prepare("SELECT id FROM roles WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $rol = $res->fetch();

    if ($rol) {
        //eliminamos el rol asociado al id recibido via GET
        $res = $mbd->prepare("DELETE FROM roles WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();

        $row = $res->rowCount();

        if ($row) {
            $msg = 'ok';
            header('Location: roles.php?m=' . $msg);
        }
    }else{
        $error = 'error';
        header('Location: roles.php?m=' . $error);   
    }
}