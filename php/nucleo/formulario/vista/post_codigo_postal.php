<?php

$codigos_postales = toba::consulta_php('consultas_mug')->get_codigo_postal($_GET['localidad']);

if (!empty($codigos_postales)) {
    foreach ($codigos_postales as $key => $value) {
        echo '<option value="'.$value['id'].'">'.$value['codigo_postal'].'</option>';
    }
}

?>