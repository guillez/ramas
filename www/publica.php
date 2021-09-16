<?php

$h = $_GET['h'];
$f = $_GET['f'];

if (!is_null($h) && !is_null($f)) {

    $params = '&h='.$h.'&f='.$f;
    $newURL = 'aplicacion.php?ai=kolla||40000174';
    header('Location: '.$newURL.$params);
} else {
    die('Faltaron parámetros necesarios para el acceso.');
}


?>