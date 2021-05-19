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

    if (isset($_GET['id'])) {
        
        $id = (int) $_GET['id'];

        //lista de roles
        $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
        $roles = $res->fetchall();

        //lista de comunas
        $res = $mbd->query("SELECT id, nombre FROM comunas ORDER BY nombre");
        $comunas = $res->fetchall();

        //consultar por una persona registrada en la tabla personas filtrada por su id
        $res = $mbd->prepare("SELECT p.id, p.nombre, p.rut, p.email, p.direccion, p.fecha_nac, p.rol_id, p.comuna_id, p.telefono, p.created_at, p.updated_at, r.nombre as rol, c.nombre as comuna FROM personas as p INNER JOIN roles as r ON p.rol_id = r.id INNER JOIN comunas as c ON p.comuna_id = c.id WHERE p.id = ?");
        $res->bindParam(1, $id);
        $res->execute();

        $persona = $res->fetch();

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
                //modificamos la persona en la tabla personas
                $res = $mbd->prepare("UPDATE personas SET nombre = ?, rut = ?, email = ?, direccion = ?, fecha_nac = ?, telefono = ?, rol_id = ?, comuna_id = ?, updated_at = now() WHERE id = ? ");
                $res->bindParam(1, $nombre);
                $res->bindParam(2, $rut);
                $res->bindParam(3, $email);
                $res->bindParam(4, $direccion);
                $res->bindParam(5, $fecha_nac);
                $res->bindParam(6, $telefono);
                $res->bindParam(7, $rol);
                $res->bindParam(8, $comuna);
                $res->bindParam(9, $id);
                $res->execute();
    
                $row = $res->rowCount();
    
                if ($row) {
                    $_SESSION['success'] = 'La persona se ha modificado correctamente';
                    header('Location: show.php?id=' . $id);
                }
            }
    
            /* echo '<pre>';
            print_r($_POST);exit;
            echo '</pre>'; */
    
        }
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
            <h1 class="text-center mt-3 text-primary">Editar Persona</h1>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <!-- validar que existe datos en el arreglo asociativo persona -->
            <?php if($persona): ?>
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="<?php echo $persona['nombre']; ?>" class="form-control" placeholder="Ingrese nombre de la persona">
                    </div>
                    <div class="form-group mb-3">
                        <label for="rut">RUT <span class="text-danger">*</span></label>
                        <input type="text" name="rut" value="<?php echo $persona['rut']; ?>" class="form-control" placeholder="Ingrese RUT de la persona">
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" value="<?php echo $persona['email']; ?>" class="form-control" placeholder="Ingrese email de la persona">
                    </div>
                    <div class="form-group mb-3">
                        <label for="direccion">Dirección <span class="text-danger">*</span></label>
                        <input type="text" name="direccion" value="<?php echo $persona['direccion']; ?>" class="form-control" placeholder="Ingrese la dirección de la persona">
                    </div>
                    <div class="form-group mb-3">
                        <label for="comuna">Comuna <span class="text-danger">*</span></label>
                        <select name="comuna" class="form-control">
                            <option value="<?php echo $persona['comuna_id']; ?>"> 
                                <?php echo $persona['comuna']; ?> 
                            </option>

                            <?php foreach($comunas as $comuna): ?>
                                <option value="<?php echo $comuna['id']; ?>"> 
                                    <?php echo $comuna['nombre']; ?> 
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="fecha">Fecha de nacimiento (opcional)</label>
                        <input type="date" name="fecha_nac" value="<?php echo $persona['fecha_nac']; ?>" class="form-control" placeholder="Ingrese la fecha de nacimiento de la persona">
                    </div>
                    <div class="form-group mb-3">
                        <label for="telefono">Telefono (solo números)<span class="text-danger">*</span></label>
                        <input type="number" name="telefono" value="<?php echo $persona['telefono']; ?>" class="form-control" placeholder="Ingrese el teléfono de la persona">
                    </div>
                    <div class="form-group mb-3">
                        <label for="rol">Rol <span class="text-danger">*</span></label>
                        <select name="rol" class="form-control">
                            <option value="<?php echo $persona['rol_id']; ?>">
                                <?php echo $persona['rol']; ?>
                            </option>

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
            <?php else: ?>
                <p class="text-info">No existen datos</p>
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