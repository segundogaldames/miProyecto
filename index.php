<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require('class/rutas.php');
    // $_POST = es un arreglo asociativo que permite recibir y/o enviar varias variables de un formulario

    //validar que los datos del formulario vienen via POST
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        #mostrar lo que viene del formulario
        print_r($_POST);

        //$post = $_POST['producto'];

        //echo "Esta es una validacion de un producto ";
    }

?>
<!-- aqui comienza el codigo del cliente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Primera Página</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <!-- llamada a naveador del sitio -->
        <?php include('partials/menu.php'); ?>
    </header>
    <div class="container">
        <h1 class="text-center mt-3 text-primary">Título de mi página</h1>
        
    </div>
    
</body>
</html>