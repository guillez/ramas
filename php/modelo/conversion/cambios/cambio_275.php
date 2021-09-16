<?php

require_once('cambio.php');

/**
 * Este script asume encuestas ya migradas.
 * Deberían estar creados los formularios que las encapsulan
 */

class cambio_275 extends cambio 
{
    private $esquema_nuevo = 'kolla_new';
    private $esquema_viejo = 'kolla';
    
	function get_descripcion()
	{
		return 'Cambio 275: Migración de los datos de las tablas de reportes de 3.1.2.';
	}

	function cambiar()
	{
        
        if ( $this->existe_tabla('kolla', 'sge_reporte_exportado') ) {
            //los registros que corresponden a reportes de encuestas simples (ya migradas)
            $sql = "INSERT INTO $this->esquema_nuevo.sge_reporte_exportado(
                                    formulario_habilitado,  
                                    reporte_tipo,
                                    fecha_desde,  
                                    fecha_hasta,  
                                    inconclusas,  
                                    multiples,  
                                    archivo,  
                                    codigos)
                              SELECT fh.formulario_habilitado, 
                                    reportes.reporte_tipo, 
                                    reportes.fecha_desde, 
                                    reportes.fecha_hasta, 
                                    reportes.inconclusas, 
                                    reportes.multiples, 
                                    reportes.archivo, 
                                    reportes.codigos
                              FROM $this->esquema_nuevo.sge_formulario_habilitado fh
                              INNER JOIN 
                                    (SELECT 
                                            sre.reporte_tipo, 
                                            sre.fecha_desde, 
                                            sre.fecha_hasta, 
                                            sre.inconclusas, 
                                            sre.multiples, 
                                            sre.archivo, 
                                            sre.codigos, 
                                            sre.encuesta, 
                                            sre.habilitacion
                                     FROM $this->esquema_viejo.sge_reportes_exportados sre 
                                            INNER JOIN $this->esquema_viejo.sge_encuesta_habilitada seh 
                                                ON (sre.habilitacion = seh.habilitacion AND sre.encuesta = seh.encuesta)
                                     ) as reportes
                                    ON (fh.habilitacion = reportes.habilitacion);";
            $this->ejecutar($sql);
        }
	}

}

?>