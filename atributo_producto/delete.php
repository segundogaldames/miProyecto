<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    $res = $mbd->prepare("SELECT id, producto_id FROM atributo_producto WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $atributo_producto = $res->fetch();

    if ($atributo_producto) {
        $res = $mbd->prepare("DELETE FROM atributo_producto WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();

        $row = $res->rowCount();

        if ($row) {
            $_SESSION['success'] = 'El atributo se ha eliminado correctamente';
            header('Location: ../productos/show.php?id=' . $atributo_producto['producto_id']);
        }
    }else {
        $_SESSION['danger'] = 'El dato no existe';
        header('Location: ../productos/show.php?id=' . $atributo_producto['producto_id']);
    }
}else{
    $_SESSION['danger'] = 'El dato no existe';
    header('Location: ../productos/show.php?id=' . $atributo_producto['producto_id']);
}