<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // $_POST = es un arreglo asociativo que permite recibir y/o enviar varias variables de un formulario

    session_start();

    //validar que los datos del formulario vienen via POST

    #llamar al archivo de conexion para usar los datos para registrar roles en la tabla roles
    require('../class/conexion.php');
    require('../class/rutas.php');

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $clave = trim(strip_tags($_POST['clave']));

        if (!$email) {
            $msg = 'Ingrese el correo electrónico';
        }elseif(!$clave){
            $msg = 'Ingrese el password';
        }else{
            $clave = sha1($clave);
            //procedemos a consultar por la existencia del email y la clave ingresada en el sistema y que este activo
            $res = $mbd->prepare("SELECT p.id, p.nombre, u.id as id_usuario, r.nombre as rol FROM personas as p INNER JOIN usuarios as u ON u.persona_id = p.id INNER JOIN roles as r ON p.rol_id = r.id WHERE p.email = ? AND u.clave = ? AND activo = 1");
            $res->bindParam(1, $email);
            $res->bindParam(2, $clave);
            $res->execute();

            $usuario = $res->fetch();

            //print_r($usuario);exit;

            if (!$usuario) {
                $msg = 'El email o la clave no están registrados';
            }else{
                $_SESSION['autenticado'] = 'si';
                $_SESSION['usuario_id'] = $usuario['id_usuario'];
                $_SESSION['usuario_nombre'] = $usuario['nombre'];
                $_SESSION['usuario_rol'] = $usuario['rol'];

                header('Location: ../index.php');
            }

            //print_r($usuario);exit;

        }
    }

?>
<!-- aqui comienza el codigo del cliente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios :: Login</title>
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
            <h1 class="text-center mt-3 text-primary">Login</h1>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Ingrese email">
                </div>
                <div class="form-group mb-3">
                    <label for="password">Password <span class="text-danger">*</span></label>
                    <input type="password" name="clave" class="form-control" placeholder="Ingrese password">
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </div>
            </form>    
            
        </div>
    </div>
    
</body>
</html>