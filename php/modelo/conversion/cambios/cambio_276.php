<?php

require_once('cambio.php');

/**
 * Este script asume encuestas ya migradas.
 * Deberían estar creados los formularios que las encapsulan
 */

class cambio_276 extends cambio
{
    private $esquema_nuevo = 'kolla_new';
    private $esquema_viejo = 'kolla';
    
	function get_descripcion()
	{
		return 'Cambio 276: Migración de los datos de las tablas de reportes de 3.3.0.';
	}

	function cambiar()
	{
        if ( $this->existe_tabla('kolla', 'sge_reporte_exportado') ) {         
            //los registros que corresponden a reportes de formularios 
            $sql = "INSERT INTO $this->esquema_nuevo.sge_reporte_exportado 
                                        (formulario_habilitado,  
                                        reporte_tipo,
                                        fecha_desde,  
                                        fecha_hasta,  
                                        inconclusas,  
                                        multiples,  
                                        archivo,  
                                        codigos)
                              SELECT sfh.formulario_habilitado, 
                                     srfe.reporte_tipo, 
                                     srfe.fecha_desde, 
                                     srfe.fecha_hasta, 
                                     0, 
                                     srfe.multiples, 
                                     srfe.archivo, 
                                     srfe.codigos
                              FROM $this->esquema_viejo.sge_reportes_forms_exportados srfe 
                                  INNER JOIN $this->esquema_viejo.sge_encuesta_habilitada seh 
                                      ON (srfe.habilitacion = seh.habilitacion)
                                  INNER JOIN $this->esquema_viejo.sge_formulario_habilitado sfh 
                                      ON (srfe.habilitacion = sfh.habilitacion AND srfe.concepto = sfh.concepto);
                              ";
            $this->ejecutar($sql);
            
            //los registros que corresponden a reportes de encuestas simples (ya migradas)
            $sql_registros = "INSERT INTO $this->esquema_nuevo.sge_reporte_exportado(
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