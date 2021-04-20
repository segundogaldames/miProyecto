<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require('../class/conexion.php');
    require('../class/rutas.php');

    //validar la existencia de la variable id que viene via GET (url)
    if (isset($_GET['id'])) {
        
        $id = (int) $_GET['id']; //parsear la variable id a numero entero

        //preguntamos si existe el id enviado via GET en la tabla comunas
        $res = $mbd->prepare("SELECT c.id, c.nombre as comuna, c.created_at, c.updated_at, r.nombre as region FROM comunas as c INNER JOIN regiones as r ON c.region_id = r.id WHERE c.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $comuna = $res->fetch();

       /*  echo '<pre>';
        print_r($comuna);exit;
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
    <title>Comunas</title>
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
            <h2 class="text-center mt-3 text-primary">Comunas</h2>
            <!-- generacion de mensaje de exito -->
            <?php if(isset($_GET['m']) && $_GET['m'] == 'ok'): ?>
                <p class="alert alert-success">
                    La comuna se ha modificado correctamente
                </p>
            <?php endif; ?>

            <!-- validar que la comuna existe     -->
            <?php if($comuna): ?>
                
                <table class="table table-hover">
                    <tr>
                        <th>Id:</th>
                        <td> <?php echo $comuna['id']; ?>  </td>
                    </tr>
                    <tr>
                        <th>Comuna:</th>
                        <td> <?php echo $comuna['comuna']; ?>  </td>
                    </tr>
                    <tr>
                        <th>Región:</th>
                        <td> <?php echo $comuna['region']; ?>  </td>
                    </tr>
                    <tr>
                        <th>Creado:</th>
                        <td> 
                            <?php 
                                //transformamos la fecha de la tabla comunas en una fecha valida para php
                                $fecha = new DateTime($comuna['created_at']);
                                echo $fecha->format('d-m-Y H:i:s'); 
                            ?>  
                        </td>
                    </tr>
                    <tr>
                        <th>Actualizado:</th>
                        <td> 
                            <?php 
                                //transformamos la fecha de la tabla comunas en una fecha valida para php
                                $fecha = new DateTime($comuna['updated_at']);
                                echo $fecha->format('d-m-Y H:i:s'); 
                            ?>  
                        </td>
                    </tr>
                </table>
                <p>
                    <a href="index.php" class="btn btn-link">Volver</a>
                    <a href="edit.php?id=<?php echo $comuna['id']; ?>" class="btn btn-primary">Editar</a>
                </p>
            <?php else: ?>
                
                <p class="text-info">El dato no existe</p>
            
            <?php endif; ?>
           
        </div>
        
    </div>
    
</body>
</html>