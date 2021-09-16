<?php

class paso_instalar_bases_kolla extends paso_instalar_bases
{

    function generar()
    {
        // Template personalizado para la pantalla de Base de Datos del instalador
        include dirname(__FILE__) . '/../templates/bases.php';
    }
}

?>