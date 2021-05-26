<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador') {
    # code...


    require('../class/conexion.php');

    if (isset($_GET['id'])) {
        
        $id = (int) $_GET['id'];

        //consulta a la tabla roles si existe un registro con el id recibido por GET
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
                $_SESSION['success'] = 'El rol se ha eliminado correctamente';
                header('Location: index.php');
            }
        }else{
            $_SESSION['danger'] = 'El rol no se ha podido eliminar';
            header('Location: index.php');   
        }
    }

}else {
    echo "<script>
        alert('Acceso Indebido');
        window.location = 'http://localhost:8888/miProyecto/';
    </script>";
}