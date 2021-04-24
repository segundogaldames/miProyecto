<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
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
        
        //almacena el nombre de la region desde el formulario
        $nombre = trim(strip_tags($_POST['nombre']));

        if (!$nombre) {
            $msg = 'Debe ingresar el nombre de la región';
        }else{

            //preguntar si la region ingresada existe en la tabla regiones
            $res = $mbd->prepare("SELECT id FROM regiones WHERE nombre = ?");
            $res->bindParam(1, $nombre);
            $res->execute();
            $region = $res->fetch();

            if ($region) {
                $msg = 'La región ya existe... intente con otra';
            }else{

                //generamos una consulta con opciones de sanitizacion de datos
                $res = $mbd->prepare("INSERT INTO regiones VALUES(null,?,now(),now())");
                //validamos por cada signo de ? el dato que intentamos enviar a la base de datos
                $res->bindParam(1, $nombre);
                //se ejecuta la consulta de insercion de datos
                $res->execute();

                //pregunte si hubo registros ingresados
                $row = $res->rowCount();

                if ($row) {
                    $msg = 'ok';
                    header('Location: index.php?m=' . $msg);
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
    <title>Peraonas</title>
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
                    <input type="text" name="nombre" class="form-control" placeholder="Ingrese nombre de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="rut">RUT <span class="text-danger">*</span></label>
                    <input type="text" name="rut" class="form-control" placeholder="Ingrese RUT de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="Ingrese email de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="direccion">Dirección <span class="text-danger">*</span></label>
                    <input type="text" name="direccion" class="form-control" placeholder="Ingrese la dirección de la persona">
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
                    <input type="date" name="fecha_nac" class="form-control" placeholder="Ingrese la fecha de nacimiento de la persona">
                </div>
                <div class="form-group mb-3">
                    <label for="telefono">Telefono (solo números)<span class="text-danger">*</span></label>
                    <input type="number" name="telefono" class="form-control" placeholder="Ingrese el teléfono de la persona">
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
                </div>
            </form>
        </div>
    </div>
    
</body>
</html>