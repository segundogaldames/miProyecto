<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    // $_POST = es un arreglo asociativo que permite recibir y/o enviar varias variables de un formulario

    //validar que los datos del formulario vienen via POST

    #llamar al archivo de conexion para usar los datos para registrar roles en la tabla roles
    require('../class/conexion.php');
    require('../class/rutas.php');

    //lista de roles
    $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
    $roles = $res->fetchall();

    //lista de comunas
    $res = $mbd->query("SELECT id, nombre FROM comunas ORDER BY nombre");
    $comunas = $res->fetchall();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        
        //alamcenamos en variables la lista de campos que viene desde el formulario via POST
        $nombre = trim(strip_tags($_POST['nombre']));
        $rut = trim(strip_tags($_POST['rut']));
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $direccion = trim(strip_tags($_POST['direccion']));
        $comuna = (int) $_POST['comuna'];
        $fecha_nac = trim(strip_tags($_POST['fecha_nac']));
        $telefono = (int) $_POST['telefono'];
        $rol = (int) $_POST['rol'];

        //print_r($_POST);exit;

        if (!$nombre || strlen($nombre) < 5) {
            $msg = 'Ingrese el nombre de la persona';
        }elseif(strlen($rut) < 9){
            $msg = 'Ingrese el rut de la persona';
        }elseif(!$email){
            $msg = 'El email no es válido';
        }elseif(!$direccion){
            $msg = 'Ingrese la dirección de la persona';
        }elseif ($comuna <= 0) {
            $msg = 'Seleccione la comuna';
        }elseif (strlen($telefono) < 9) {
            $msg = 'El número de teléfono ingresado no es válido';
        }elseif($rol <= 0){
            $msg = 'Seleccione el rol';
        }else{
            //procedemos a verificar que el rut y el email del usuario no exista
            $res = $mbd->prepare("SELECT id FROM personas WHERE rut = ? AND email = ?");
            $res->bindParam(1, $rut);
            $res->bindParam(2, $email);
            $res->execute();

            $persona = $res->fetch();

            if ($persona) {
                $msg = 'La persona ingresada ya existe... intente con otra';
            }else{
                //registramos la persona en la tabla personas
                $res = $mbd->prepare("INSERT INTO personas VALUES(null, ?, ?, ?, ?, ?, ?, ?, ?, now(), now() ) ");
                $res->bindParam(1, $nombre);
                $res->bindParam(2, $rut);
                $res->bindParam(3, $email);
                $res->bindParam(4, $direccion);
                $res->bindParam(5, $fecha_nac);
                $res->bindParam(6, $telefono);
                $res->bindParam(7, $rol);
                $res->bindParam(8, $comuna);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'La persona se ha registrado correctamente';
                    header('Location: index.php');
                }
            }
        }

        /* echo '<pre>';
        print_r($_POST);exit;
        echo '</pre>'; */

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
    <title>Personas</title>
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
            <h1 class="text-center mt-3 text-primary">Nueva Persona</h1>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group mb-3">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" value="<?php if(isset($_POST['nombre'])) echo $_POST['nombre']; ?>" class="form-control" placeholder="Ingrese nombre de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="rut">RUT <span class="text-danger">*</span></label>
                    <input type="text" name="rut" value="<?php if(isset($_POST['rut'])) echo $_POST['rut']; ?>" class="form-control" placeholder="Ingrese RUT de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>" class="form-control" placeholder="Ingrese email de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="direccion">Dirección <span class="text-danger">*</span></label>
                    <input type="text" name="direccion" value="<?php if(isset($_POST['direccion'])) echo $_POST['direccion']; ?>" class="form-control" placeholder="Ingrese la dirección de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="comuna">Comuna <span class="text-danger">*</span></label>
                    <select name="comuna" class="form-control">
                        <option value="">Seleccione...</option>
                        <?php foreach($comunas as $comuna): ?>
                            <option value="<?php echo $comuna['id']; ?>"> 
                                <?php echo $comuna['nombre']; ?> 
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group mb-3">
                    <label for="fecha">Fecha de nacimiento (opcional)</label>
                    <input type="date" name="fecha_nac" value="<?php if(isset($_POST['fecha_nac'])) echo $_POST['fecha_nac']; ?>" class="form-control" placeholder="Ingrese la fecha de nacimiento de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="telefono">Telefono (solo números)<span class="text-danger">*</span></label>
                    <input type="number" name="telefono" value="<?php if(isset($_POST['telefono'])) echo $_POST['telefono']; ?>" class="form-control" placeholder="Ingrese el teléfono de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="rol">Rol <span class="text-danger">*</span></label>
                    <select name="rol" class="form-control">
                        <option value="">Seleccione...</option>
                        <?php foreach($roles as $rol): ?>
                            <option value="<?php echo $rol['id']; ?>"> 
                                <?php echo $rol['nombre']; ?> 
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="index.php" class="btn btn-link">Volver</a>
                </div>
            </form>
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