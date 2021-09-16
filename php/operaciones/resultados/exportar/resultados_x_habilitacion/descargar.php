<?php
toba::memoria()->desactivar_reciclado();

$archivo = toba::memoria()->get_dato('nombre_archivo') . '.txt';
$path_reportes = toba::proyecto()->get_path().'/procesos/reportes/';
$path_archivo = $path_reportes . $archivo;

if (file_exists($path_archivo)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');;
    header('Content-Disposition: attachment; filename="'.basename($archivo).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path_archivo));
    readfile($path_archivo);
    //exit;
}

?>