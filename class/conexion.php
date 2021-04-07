<?php

$user = 'root';
$pass = 'root'; #clave vacia para usuarios de windows

try {
    $mbd = new PDO('mysql:host=localhost;dbname=miproyecto', $user, $pass);
    //echo 'La base de datos esta conectada';
    /* foreach($mbd->query('SELECT * from FOO') as $fila) {
        print_r($fila);
    }
    $mbd = null; */
} catch (PDOException $e) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}
?>