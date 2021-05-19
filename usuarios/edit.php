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

    if (isset($_GET['id_persona'])) {
        
        $id_persona = (int) $_GET['id_persona'];

        //rescatar el usuario y algun dato de la persona
        $res = $mbd->prepare("SELECT u.id, u.activo, p.nombre as persona FROM usuarios as u INNER JOIN personas as p ON u.persona_id = p.id WHERE u.persona_id = ?");
        $res->bindParam(1, $id_persona);
        $res->execute();

        $usuario = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            
            $activo = (int) $_POST['activo'];

            if ($activo <= 0) {
                $msg = 'Seleccione el estado del usuario';
            }else{
                //rescatamos el id del usuario
                $id = $usuario['id'];
                //actualizar el estado del usuario
                $res = $mbd->prepare("UPDATE usuarios SET activo = ?, updated_at = now() WHERE id = ? ");
                $res->bindParam(1, $activo);
                $res->bindParam(2, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'El estado del usuario se ha modificado correctamente';
                    header('Location: ../personas/show.php?id=' . $id_persona);
                }
            }
        }

        //print_r($usuario);exit;
    }
    
?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 'Administrador'): ?>
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

            <?php if($usuario): ?>
                <h4>Modificando estado a <?php echo $usuario['persona']; ?> </h4>
                <hr>
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="activo">Estado <span class="text-danger">*</span></label>
                        <select name="activo" class="form-control">
                            <option value="<?php echo $usuario['activo']; ?>">
                                <?php if($usuario['activo'] == 1): ?>
                                    Activo
                                <?php else: ?>
                                    Inactivo
                                <?php endif; ?>
                            </option>

                            <option value="1">Activar</option>
                            <option value="2">Desactivar</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="../personas/show.php?id=<?php echo $id_persona; ?>" class="btn btn-link">Volver</a>
                    </div>
                </form>
            <?php else: ?>
                <p class="text-info">El usuario no tiene una cuenta registrada...
                <a href="../personas/show.php?id=<?php echo $id_persona; ?>" class="btn btn-link btn-sm">Volver</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
    
</body>
</html>
<?php else: ?>
    <!-- Acceso Indebido -->
    <script>
        alert('Acceso Indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>