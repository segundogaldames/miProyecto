<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //iniciar la creacion de variables de sesion
    session_start();
    // $_POST = es un arreglo asociativo que permite recibir y/o enviar varias variables de un formulario

    //validar que los datos del formulario vienen via POST

    #llamar al archivo de conexion para usar los datos para registrar roles en la tabla roles
    require('../class/conexion.php');
    require('../class/rutas.php');

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        
        
        if (isset($_POST['id'])) {
            $_SESSION['id_persona'] = (int) $_POST['id'];
        }

        //consulta a la tabla personas por la variable de session de la persona
        $res = $mbd->prepare("SELECT id, nombre FROM personas WHERE id = ?");
        $res->bindParam(1, $_SESSION['id_persona']);
        $res->execute();

        $persona = $res->fetch();


        //print_r($_SESSION['id_persona']);

        //esta validacion se hara efectiva cuando llenemos el formulario de modificacion de password
        if (isset($_POST['modify']) && $_POST['modify'] == 1) {
            $password = trim(strip_tags($_POST['password']));
            $repassword = trim(strip_tags($_POST['repassword']));

            if (!$password || strlen($password) < 8) {
                $msg = 'Ingrese el password del usuario con al menos 8 caracteres';
            }elseif($repassword != $password){
                $msg = 'El password no coincide';
            }else{
                //modificamos el password del usuario
                //print_r($_SESSION['id_persona']);exit;
                $password = sha1($password);
                $res = $mbd->prepare("UPDATE usuarios SET clave = ?, updated_at = now() WHERE id = ?");
                $res->bindParam(1, $password);
                $res->bindParam(2, $_SESSION['id_persona']);
                $res->execute();

                $row = $res->rowCount();

                //print_r($row);exit;

                if ($row) {
                    $_SESSION['success'] = 'El password se ha modificado correctamente';
                    header('Location: ../personas/show.php?id=' . $_SESSION['id_persona']);
                }
            }
        }
        

        //print_r($_POST);exit;
    }
?>

<?php if($_SESSION['usuario_rol'] == 'Administrador' || ($_SESSION['usuario_nombre'] == $persona['nombre'])): ?>
<!-- aqui comienza el codigo del cliente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <!-- llamada a navegador del sitio -->
        <?php include('../partials/menu.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h1 class="text-center mt-3 text-primary">Modificar Usuario</h1>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <?php if($persona): ?>
                <h4>Modificando password a <?php echo $persona['nombre']; ?> </h4>
                <hr>
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Ingrese el password" oncopy="return false">
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Confirmar Password <span class="text-danger">*</span></label>
                        <input type="password" name="repassword" class="form-control" placeholder="Confirmar password" onpaste="return false">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="confirm" value="1">
                        <input type="hidden" name="modify" value="1">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="../personas/show.php?id=<?php echo $_SESSION['id_persona']; ?>" class="btn btn-link">Volver</a>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>
        </div>
    </div>
    
</body>
</html>
<?php else: ?>
    <!-- Acceso Indebido -->
    <script>
        alert('Acceso Indebido');
        window.location ='http://localhost:8888/miProyecto/';
    </script>
<?php endif; ?>