<?php

class ci_obtener_archivo extends ci_navegacion_por_ug
{
    protected $s__filtro;
    
    function conf__filtro(toba_ei_filtro $filtro)
    {
        $filtro->columna('exportado_codigo')->set_condicion_fija('es_igual_a', true);
        
        if (isset($this->s__filtro)) {
            return $this->s__filtro;
        }
    }
    
    function evt__filtro__filtrar($datos)
    {
        $this->s__filtro = $datos;
    }
    
    function evt__filtro__cancelar()
    {
        unset($this->s__filtro);
    }
    
    function conf__cuadro(toba_ei_cuadro $cuadro)
    {
        $this->set_ug();
        $filtro_ug = "ug.unidad_gestion = ".kolla_db::quote($this->s__ug);
        $filtro    = $this->dep('filtro')->get_sql_where();
        $filtro    = $filtro ? "$filtro AND $filtro_ug " : $filtro_ug;
        $cuadro->set_datos(kolla::co('consultas_reportes')->get_reportes_usuario_con_ug($filtro));
    }
    
    function evt__cuadro__eliminar($seleccion)
    {
        $path_archivo = toba::proyecto()->get_path().'/procesos/reportes/'.$seleccion['archivo'].'.txt';
        $sql = "DELETE FROM sge_reporte_exportado WHERE exportado_codigo = " . toba::db()->quote($seleccion['exportado_codigo']);
        try {
            toba::db()->ejecutar($sql);
        } catch (toba_error_db $ex) {
            throw $ex;
        }
        
        if (file_exists($path_archivo)) {
            $eliminado = unlink($path_archivo);
            if (!$eliminado) {
                toba::notificacion()->error('El archivo de reporte no pudo ser eliminado. Contacte al administrador del sistema.');
            } else {
                toba::notificacion()->info('El archivo de reporte fue eliminado correctamente');
            }
        }
    }
    
    function servicio__ejecutar()
    {
        // Recupero la clave original
        $clave = toba::memoria()->get_parametro('fila_safe');
        $clave = toba_ei_cuadro::recuperar_clave_fila('45000114', $clave);
        // Verifico que el archivo exista
        $path_reportes  = toba::proyecto()->get_path().'/procesos/reportes/';
        $archivo        = $clave['archivo'].'.txt';
        //var_dump($archivo);
        $path_archivo   = $path_reportes.$archivo;
        // Si el archivo existe lo descargo
        if (file_exists($path_archivo)) {
            $maxFileSize = $this->convertBytes(ini_get('upload_max_filesize'));
            $file_size = filesize($path_archivo);
            
            //si supera el máximo determinado no se podrá descargar
            if($file_size > $maxFileSize) {
                $file_size_mb = round($file_size / 1048576, 2);
                $mensaje_error = "El archivo supera el tamaño permitido para descargar (".$file_size_mb."MB). Consulte con su administrador."
                        . "El archivo se generó con el nombre: ".$archivo;

                $archivo = 'error_'.$clave['exportado_codigo'].'.txt';
                header('Cache-Control: private');
                header('Content-type: application/text/plain');
                header('Content-Length: '.strlen(ltrim($mensaje_error)));
                header('Content-Disposition: attachment; filename='.$archivo);
                header('Pragma: no-cache');
                header('Expires: 0');
                echo $mensaje_error;
            } else {
                $resultados = file_get_contents($path_archivo);
                header('Cache-Control: private');
                header('Content-type: application/text/plain');
                header('Content-Length: '.strlen(ltrim($resultados)));
                header('Content-Disposition: attachment; filename='.$archivo);
                header('Pragma: no-cache');
                header('Expires: 0');
                echo $resultados;
            }
        } else {
            throw new toba_error('El archivo '.$path_archivo.'no existe. El mismo puede haber sido eliminado del servidor o bien puede estar generandose. Intente nuevamente más tarde.');
        }
    }
    
    function convertBytes($value)
    {
        if (is_numeric($value)) {
            return $value;
        } else {
            $value_length = strlen($value);
            $qty = substr($value, 0, $value_length - 1);
            $unit = strtolower(substr($value, $value_length - 1));
            switch ($unit) {
                case 'k':
                    $qty *= 1024;
                    break;
                case 'm':
                    $qty *= 1048576;
                    break;
                case 'g':
                    $qty *= 1073741824;
                    break;
            }
            return $qty;
        }
    }        
    
}