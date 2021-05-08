<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

    //print_r($_SESSION);exit;
    
    require('../class/conexion.php');
    require('../class/rutas.php');

    if (isset($_GET['id'])) {
        
        $id = (int) $_GET['id'];

        //consultar por una persona registrada en la tabla personas filtrada por su id
        $res = $mbd->prepare("SELECT p.id, p.nombre, p.rut, p.email, p.direccion, p.fecha_nac, p.telefono, p.created_at, p.updated_at, r.nombre as rol, c.nombre as comuna FROM personas as p INNER JOIN roles as r ON p.rol_id = r.id INNER JOIN comunas as c ON p.comuna_id = c.id WHERE p.id = ?");
        $res->bindParam(1, $id);
        $res->execute();

        $persona = $res->fetch();

        //consultar si la persona con id tiene un usuario
        $res = $mbd->prepare("SELECT id FROM usuarios WHERE persona_id = ?");
        $res->bindParam(1, $id);
        $res->execute();

        $usuario = $res->fetch();

        /* echo '<pre>';
        print_r($persona);exit;
        echo '</pre>'; */
    }
?>
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
            <h2 class="text-center mt-3 text-primary">Personas</h2>
            <!-- generacion de mensajes de exito o error -->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <p class="alert alert-success">
                    La persona se ha modificado correctamente
                </p>
            <?php endif; ?>

            <?php include('../partials/mensajes.php'); ?>

            <?php if($persona): ?>
                <table class="table table-hover">
                    <tr>
                        <th>Nombre:</th>
                        <td> <?php echo $persona['nombre']; ?> </td>
                    </tr>
                    <tr>
                        <th>RUT:</th>
                        <td> <?php echo $persona['rut']; ?> </td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td> <?php echo $persona['email']; ?> </td>
                    </tr>
                    <tr>
                        <th>Dirección:</th>
                        <td> <?php echo $persona['direccion']; ?> </td>
                    </tr>
                    <tr>
                        <th>Comuna:</th>
                        <td> <?php echo $persona['comuna']; ?> </td>
                    </tr>
                    <tr>
                        <th>Fecha nacimiento:</th>
                        <td> 
                            <?php 
                                $fecha_nac = new DateTime($persona['fecha_nac']);
                                echo $fecha_nac->format('d-m-Y'); 
                            ?> 
                        </td>
                    </tr>
                    <tr>
                        <th>Teléfono:</th>
                        <td> <?php echo $persona['telefono']; ?> </td>
                    </tr>
                    <tr>
                        <th>Rol:</th>
                        <td> <?php echo $persona['rol']; ?> </td>
                    </tr>
                    <tr>
                        <th>Creado:</th>
                        <td> 
                            <?php 
                                $created = new DateTime($persona['created_at']);
                                echo $created->format('d-m-Y H:i:s'); 
                            ?> 
                        </td>
                    </tr>
                    <tr>
                        <th>Actualizado:</th>
                        <td> 
                            <?php 
                                $updated = new DateTime($persona['updated_at']);
                                echo $updated->format('d-m-Y H:i:s'); 
                            ?> 
                        </td>
                    </tr>
                </table>
                <p>
                    <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-primary">Editar</a>
                    <a href="index.php" class="btn btn-link">Volver</a>
                    <!-- verificar si la persona del id tiene un usuario -->
                    <?php if($usuario): ?>
                        <form action="../usuarios/editPassword.php" method="post">
                            <input type="hidden" name="confirm" value="1">
                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                            <button type="submit" class="btn btn-warning">Modificar Password</button>
                        </form>
                    <?php else: ?>
                        <a href="../usuarios/add.php?id=<?php echo $id; ?>" class="btn btn-success">Agregar Password</a>
                    <?php endif; ?>

                    
                    
                </p>
            <?php else: ?>
                <p class="text-info">El dato no existe</p>
            <?php endif; ?>    
            
        </div>
        
    </div>
    
</body>
</html>