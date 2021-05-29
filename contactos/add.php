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
        
        //almacena el nombre de la region desde el formulario
        $nombre = trim(strip_tags($_POST['nombre']));
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $telefono = (int) $_POST['telefono'];
        $asunto = (int) $_POST['asunto'];
        $mensaje = trim(strip_tags($_POST['mensaje']));

        if (strlen($nombre) < 4) {
            $msg = 'Ingrese su nombre';
        }elseif (!$email) {
            $msg = 'Ingrese un correo electrónico válido';
        }elseif (strlen($telefono) < 9) {
            $msg = 'Ingrese al menos 9 dígitos';
        }elseif ($asunto <= 0) {
            $msg = 'Seleccione un asunto';
        }elseif (strlen($mensaje) < 10) {
            $msg = 'Ingrese un mensaje de al menos 10 caracteres';
        }else{
            //procedemos a registrar el contacto
            // 1 => pendiente, 2 => procesado
            $res = $mbd->prepare("INSERT INTO contactos VALUES(null, ?, ?, ?, ?, ?, 1, now(), now() ) ");
            $res->bindParam(1, $nombre);
            $res->bindParam(2, $email);
            $res->bindParam(3, $telefono);
            $res->bindParam(4, $asunto);
            $res->bindParam(5, $mensaje);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $_SESSION['success'] = 'Gracias por comunicarse con nosotros, pronto le contactaremos...';
                header('Location: ../index.php');
            }
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
    <title>Contactos</title>
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
            <h1 class="text-center mt-3 text-primary">Nuevo Mensaje</h1>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group mb-3">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" value="<?php if(isset($_POST['nombre'])) echo $_POST['nombre']; ?>" class="form-control" placeholder="Ingrese su nombre completo">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" class="form-control" placeholder="Ingrese su correo electrónico">
                </div>
                <div class="form-group mb-3">
                    <label for="telefono">Teléfono de contacto (solo números) <span class="text-danger">*</span></label>
                    <input type="number" name="telefono" value="<?php if(isset($_POST['telefono'])) echo $_POST['telefono']; ?>" class="form-control" placeholder="Ingrese su teléfono de contacto">
                </div>
                <div class="form-group mb-3">
                    <label for="asunto">Asunto <span class="text-danger">*</span></label>
                    <select name="asunto" class="form-control">
                        <option value="">Seleccione...</option>
                        <option value="1">Reclamo</option>
                        <option value="2">Despacho</option>
                        <option value="3">Consulta General</option>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="mensaje">Mensaje <span class="text-danger">*</span></label>
                    <textarea name="mensaje" class="form-control" rows="4" placeholder="Ingrese su mensaje" style="resize:none">
                        <?php if(isset($_POST['mensaje'])) echo $_POST['mensaje']; ?>
                    </textarea>
                </div>
                <div class="form-group">
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="../index.php" class="btn btn-link">Volver</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>