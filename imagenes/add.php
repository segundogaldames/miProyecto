<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    // $_POST = es un arreglo asociativo que permite recibir y/o enviar varias variables de un formulario

    session_start();
    //validar que los datos del formulario vienen via POST

    #llamar al archivo de conexion para usar los datos para registrar roles en la tabla roles
    require('../class/conexion.php');
    require('../class/rutas.php');

    if (isset($_GET['id_producto'])) {
        $id_producto = (int) $_GET['id_producto'];

        //producto por el id ingresado
        $res = $mbd->prepare("SELECT id, nombre FROM productos WHERE id = ?");
        $res->bindParam(1, $id_producto);
        $res->execute();
        $producto = $res->fetch();

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        
            $titulo = trim(strip_tags($_POST['titulo']));
            $descripcion = trim(strip_tags($_POST['descripcion']));
            $imagen = $_FILES['imagen']['name'];
            $dir_tmp = $_FILES['imagen']['tmp_name'];

            //print_r($imagen);exit;
    
            if (strlen($titulo) < 5) {
                $msg = 'Ingrese al menos 5 caracteres al título de la imagen';
            }elseif (strlen($descripcion) < 10) {
                $msg = 'Ingrese al menos 10 caracteres en la descripción de la imagen';
            }elseif (!$imagen) {
                $msg = 'Ingrese una imagen';
            }else{
    
                //preguntar si la imagen ingresada existe en la tabla imagenes
                $res = $mbd->prepare("SELECT id FROM imagenes WHERE imagen = ?");
                $res->bindParam(1, $imagen);
                $res->execute();
                $img = $res->fetch();
    
                if ($img) {
                    $msg = 'La imagen ingresada ya existe... intente con otra';
                }else{
                    //guardar la imagen subida al directorio definitivo en el servidor
                    $upload = $_SERVER['DOCUMENT_ROOT'] . '/miProyecto/productos/img/';
                    //print_r($upload);exit;
                    $img_subida = $upload . basename($_FILES['imagen']['name']);

                    //comprobamos que la imagen se ha subido al servidor
                    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $img_subida)) {
                        //verificar que no haya otra imagen del producto que sea portada
                        $res = $mbd->prepare("SELECT id FROM imagenes WHERE producto_id = ? AND portada = 1");
                        $res->bindParam(1, $id_producto);
                        $res->execute();
                        $portada = $res->fetch();

                        //imagen de portada = 1; imagen general = 2
                        if ($portada) {
                            $portada = 2;
                        }else{
                            $portada = 1;
                        }
                        //print_r($imagen);exit;
                        //generamos una consulta con opciones de sanitizacion de datos
                        $res = $mbd->prepare("INSERT INTO imagenes VALUES(null, ?, ?, 1, ?, ?, ?, now(), now() )");
                        //validamos por cada signo de ? el dato que intentamos enviar a la base de datos
                        $res->bindParam(1, $titulo);
                        $res->bindParam(2, $imagen);
                        $res->bindParam(3, $portada);
                        $res->bindParam(4, $descripcion);
                        $res->bindParam(5, $id_producto);
                        //se ejecuta la consulta de insercion de datos
                        $res->execute();
        
                        //pregunte si hubo registros ingresados
                        $row = $res->rowCount();
        
                        if ($row) {
                            $_SESSION['success'] = 'La imagen se ha registrado correctamente';
                            header('Location: ../productos/show.php?id=' . $id_producto);
                        }
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
    <title>Marcas</title>
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
            <h4 class="text-left mt-3 text-secondary">Nueva imagen para <?php echo $producto['nombre']; ?></h4>
            <!-- mostrar mensaje de error -->
            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>
            
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group mb-3">
                    <label for="titulo">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" class="form-control" placeholder="Ingrese el título de la imagen" value="<?php if(isset($_POST['titulo'])) echo $_POST['titulo'] ?>">
                </div>
                <div class="form-group mb-3">
                     <label for="descripcion">Descripción <span class="text-danger">*</span></label>
                     <textarea name="descripcion" class="form-control" rows="4">
                        <?php if(isset($_POST['descripcion'])) echo $_POST['descripcion'] ?>
                     </textarea>     
                </div>
                <div class="form-group mb-3">
                    <label for="imagen">Imagen <span class="text-danger">*</span></label>
                    <input type="file" name="imagen" class="form-control">
                </div>
                <div class="form-group">
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <a href="../productos/show.php?id=<?php echo $id_producto; ?>" class="btn btn-link">Volver</a>
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>