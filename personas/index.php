<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require('../class/conexion.php');
    require('../class/rutas.php');

    //consultar por la lista de personas registrada en la tabla personas
    $res = $mbd->query("SELECT p.id, p.nombre, p.rut, p.email, r.nombre as rol, c.nombre as comuna FROM personas as p INNER JOIN roles as r ON p.rol_id = r.id INNER JOIN comunas as c ON p.comuna_id = c.id");
    $personas = $res->fetchall();

    /* echo '<pre>';
    print_r($personas);exit;
    echo '</pre>'; */

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
        <div class="col-md-10 offset-md-1">
            <h2 class="text-center mt-3 text-primary">Personas</h2>
            <!-- generacion de mensajes de exito o error -->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <p class="alert alert-success">
                    La persona se ha registrado correctamente
                </p>
            <?php endif; ?>

            <?php if(isset($_GET['e']) && $_GET['e'] == 'ok'): ?>
                <p class="alert alert-success">
                    La persona se ha eliminado correctamente
                </p>
            <?php endif; ?>

            <?php if(isset($_GET['error']) && $_GET['error'] == 'error'): ?>
                <p class="alert alert-danger">
                    El dato no existe
                </p>
            <?php endif; ?>

            <table class="table table-hover">
                <tr>
                    <th>Nombre</th>
                    <th>RUT</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Comuna</th>
                </tr>
                <?php foreach($personas as $persona): ?>
                    <tr>
                        <td> 
                            <a href="show.php?id=<?php echo $persona['id']; ?> ">
                                <?php echo $persona['nombre']; ?> 
                            </a>
                        </td>
                        <td> <?php echo $persona['rut']; ?> </td>
                        <td> <?php echo $persona['email']; ?> </td>
                        <td> <?php echo $persona['rol']; ?> </td>
                        <td> <?php echo $persona['comuna']; ?> </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <a href="add.php" class="btn btn-primary">Nueva Persona</a>
        </div>
        
    </div>
    
</body>
</html>