<?php

    kolla::logger()->debug('Item p�blico');

    $habilitacion = toba::memoria()->get_parametro('h');
    $form_hab = toba::memoria()->get_parametro('f');

    require_once 'pub_completar.php';
    $acceso = new pub_completar();
    $acceso->procesar();
?>
