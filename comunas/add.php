<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    #llamar al archivo de conexion para usar los datos para registrar roles en la tabla roles
    require('../class/conexion.php');
    require('../class/rutas.php');
    // $_POST = es un arreglo asociativo que permite recibir y/o enviar varias variables de un formulario

    //comprobamos la existencia del id de region que viene via GET
    if (isset($_GET['id'])) {
        
        $id_region = (int) $_GET['id'];//guardamos el id de la region

        //print_r($id_region);exit;

        //validamos el formulario 
        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            
            $nombre = trim(strip_tags($_POST['nombre']));//guardamos el nombre de la comuna de manera sanitizada

            if (!$nombre) {
                $msg = 'Debe ingresar el nombre de la comuna';
            }else{
                //verificar que la comuna a ingresar no exista en la tabla comunas
                $res = $mbd->prepare("SELECT id FROM comunas WHERE nombre = ?");
                $res->bindParam(1, $nombre);
                $res->execute();
                $comuna = $res->fetch();

                //print_r($comuna);exit;
                if ($comuna) {
                    $msg = 'La comuna ingresada ya existe... intente con otra';
                }else{
                    //registramos la comuna con la region asociada
                    $res = $mbd->prepare("INSERT INTO comunas VALUES(null, ?, ?, now(), now() )");
                    $res->bindParam(1, $nombre);
                    $res->bindParam(2, $id_region);
                    $res->execute();

                    $row = $res->rowCount(); //recuperamos el numero de filas afectadas en la consulta;

                    //print_r($row);exit;
                    if ($row) {
                        $msg = 'ok';
                        header('Location: index.php?m=' . $msg); //redireccionando hacia index de comunas 
                    }
                }
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
            <h1 class="text-center mt-3 text-primary">Nueva Comuna</h1>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <form action="" method="post">
                <div class="form-group mb-3">
                    <label for="nombre">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control" placeholder="Ingrese nombre de la comuna">
                </div>
                <div class="form-group">
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="../regiones/show.php?id=<?php echo $id_region; ?>" class="btn btn-link">Volver</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>