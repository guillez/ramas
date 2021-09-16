<?php

require_once('cambio.php');

class cambio_388 extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 388 : Administración de habilitaciones por dependencia (unidad de gestión)';
    }
    
    function cambiar()
    {
        $sql = "ALTER TABLE sge_habilitacion ADD COLUMN unidad_gestion Varchar;
                
                CREATE INDEX ifk_sge_habilitacion_sge_unidad_gestion ON  sge_habilitacion (unidad_gestion); 
                
                ALTER TABLE sge_habilitacion
                    ADD CONSTRAINT fk_sge_habilitacion_sge_unidad_gestion 
                    FOREIGN KEY (unidad_gestion) REFERENCES sge_unidad_gestion (unidad_gestion);
                ";
        
        $this->ejecutar($sql);

        $sql = "UPDATE sge_habilitacion hab
                SET unidad_gestion = valores.ug
                FROM (SELECT DISTINCT ea.unidad_gestion as ug, h.habilitacion
                        FROM sge_encuesta_atributo ea 
                            INNER JOIN sge_formulario_habilitado_detalle fhd ON (ea.encuesta = fhd.encuesta)
                            INNER JOIN sge_formulario_habilitado fh ON (fhd.formulario_habilitado = fh.formulario_habilitado)
                            INNER JOIN sge_habilitacion h ON (fh.habilitacion = h.habilitacion)) AS valores
                WHERE hab.habilitacion = valores.habilitacion";
        $this->ejecutar($sql);
    }

} 
