<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    
    require('../class/conexion.php');
    require('../class/rutas.php');

    //validar la existencia de la variable id que viene via GET (url)
    if (isset($_GET['id'])) {
        
        $id = (int) $_GET['id']; //parsear la variable id a numero entero

        //preguntamos si existe el id enviado via GET en la tabla comunas
        $res = $mbd->prepare("SELECT c.id, c.nombre as comuna, c.region_id, r.nombre as region FROM comunas as c INNER JOIN regiones as r ON c.region_id = r.id WHERE c.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $comuna = $res->fetch();

        /* echo '<pre>';
        print_r($comuna);exit;
        echo '</pre>'; */

        //validar que el formulario viene via POST
        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            
            $nombre = trim(strip_tags($_POST['nombre']));
            $region = (int) $_POST['region']; //recuperamos y guardamos la region

            if (!$nombre) {
                $msg = 'Ingrese el nombre de la comuna';
            }elseif(!$region){
                $msg = 'Seleccione una región';
            }else{
                $res = $mbd->prepare("UPDATE comunas SET nombre = ?, region_id = ?, updated_at = now() WHERE id = ?");
                $res->bindParam(1, $nombre);
                $res->bindParam(2, $region);
                $res->bindParam(3, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'La comuna se ha modificado correctmente';
                    header('Location: show.php?id=' . $id);
                }
            }
            //print_r($_POST);exit;
        }

        //print_r($rol);exit;
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
        <!-- llamada a naveador del sitio -->
        <?php include('../partials/menu.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mt-3 text-primary">Editar Comuna</h2>
            <!-- generacion de mensaje de error -->
            <?php if(isset($msg)): ?> 
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>           

            <!-- validar que el comuna existe     -->
            <?php if($comuna): ?>
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="<?php echo $comuna['comuna']; ?>" class="form-control" placeholder="Ingrese nombre de la comuna">
                    </div>
                    <div class="form-group mb-3">
                        <label for="region">Región <span class="text-danger">*</span></label>
                        <select name="region" class="form-control">
                            <!-- recuperar el id y la region que tiene registrada la comuna -->
                            <option value="<?php echo $comuna['region_id']; ?>"> 
                                <?php echo $comuna['region']; ?> 
                            </option>

                            <!-- mostrar la lista de regiones disponibles para editar -->
                            <option value="">Seleccione...</option>
                            
                            <?php
                                //lista de regiones disponibles
                                $sql = $mbd->query("SELECT id, nombre FROM regiones ORDER BY nombre");
                                $regiones = $sql->fetchall();
                            ?>
                            <!-- generamos la lista de regiones a traves de foreach -->
                            <?php foreach($regiones as $region): ?>
                                <option value="<?php echo $region['id']; ?>"> 
                                    <?php echo $region['nombre'] ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-primary">Editar</button>
                        <a href="show.php?id=<?php echo $comuna['id']; ?>" class="btn btn-link">Volver</a>
                    </div>
                </form>
            <?php else: ?>
                
                <p class="text-info">El dato no existe</p>
            
            <?php endif; ?>
           
        </div>
        
    </div>
    
</body>
</html>