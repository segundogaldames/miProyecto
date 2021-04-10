<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require('class/conexion.php');

    //consultar por la lista de roles registrada en la tabla roles
    $res = $mbd->query("SELECT id, nombre FROM roles ORDER BY nombre");
    $roles = $res->fetchall();

    //print_r($roles);

?>
<!-- aqui comienza el codigo del cliente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <!-- llamada a naveador del sitio -->
        <?php include('partials/cabecera.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mt-3 text-primary">Roles</h2>
            <!-- generacion de mensaje de exito -->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <p class="alert alert-success">
                    El rol se ha registrado correctamente
                </p>
            <?php endif; ?>

            <table class="table table-hover">
                <tr>
                    <th>Código</th>
                    <th>Rol</th>
                </tr>
                <?php foreach($roles as $rol): ?>
                    <tr>
                        <td> <?php echo $rol['id']; ?> </td>
                        <td>
                            <a href="verRol.php?id=<?php echo $rol['id']; ?>"> <?php echo $rol['nombre']; ?>  </a>
                            
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <a href="addRoles.php" class="btn btn-primary">Nuevo Rol</a>
        </div>
        
    </div>
    
</body>
</html>