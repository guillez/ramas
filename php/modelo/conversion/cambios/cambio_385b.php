<?php

require_once('cambio.php');

class cambio_385b extends cambio
{
    function get_descripcion()
    {
        return 'Cambio 385b : Incorporación de Unidad de Gestión por defecto para el sistema';
    }
    
    function cambiar()
    {      
        //se asigna la unidad de gestión por defecto a todas las entidades que se migraron
        //al esquema nuevo y que no tienen unidad de gestion
        $sqls[] = "UPDATE sge_encuesta_atributo SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_concepto          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_elemento          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_pregunta          SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_respuesta         SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        $sqls[] = "UPDATE sge_habilitacion      SET unidad_gestion = 0 WHERE unidad_gestion IS NULL";
        
        $this->ejecutar($sqls);
    }

} 