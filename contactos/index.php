<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    
    require('../class/conexion.php');
    require('../class/rutas.php');

    //consultar por la lista de comunas registrada en la tabla comunas y la region a la que pertenece
    $res = $mbd->query("SELECT id, nombre, email, estado, created_at FROM contactos ORDER BY id DESC");
    $contactos = $res->fetchall();

    //print_r($contactos);exit;

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
        <div class="col-md-10 offset-md-1">
            <h2 class="text-center mt-3 text-primary">Contactos</h2>
            <!-- generacion de mensajes de exito o error -->
            <?php include('../partials/mensajes.php'); ?>

            <table class="table table-hover">
                <tr>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                </tr>
                <?php foreach($contactos as $contacto): ?>
                    <tr>
                        <td>
                            <a href="show.php?id=<?php echo $contacto['id']; ?>">
                                <?php echo $contacto['nombre']; ?> 
                            </a>  
                        </td>
                        <td>
                            <?php echo $contacto['email']; ?>                              
                        </td>
                        <td>
                            <?php if($contacto['estado'] == 1): ?>
                                Pendiente
                            <?php else: ?>
                                Procesado
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $fecha = new DateTime($contacto['created_at']);
                                echo $fecha->format('d-m-Y H:i:s');
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        
    </div>
    
</body>
</html>