<?php

class kolla_migracion_4_0_0 extends kolla_migracion
{
    function negocio__627()
    {
        $sql = "ALTER TABLE sge_pregunta ADD COLUMN ayuda character varying;";
        $this->get_db()->ejecutar($sql);
    }
        
    function negocio__664()
    {
        $dir = $this->get_dir_ddl();
        $archivos = array(
            $dir.'/10_Secuencias/sge_evaluacion_seq.sql',
            $dir.'/10_Secuencias/sge_puntaje_aplicacion_seq.sql',
            $dir.'/10_Secuencias/sge_puntaje_pregunta_seq.sql',
            $dir.'/10_Secuencias/sge_puntaje_respuesta_seq.sql',
            $dir.'/10_Secuencias/sge_puntaje_seq.sql',
            $dir.'/20_Tablas/sge_evaluacion.sql',
            $dir.'/20_Tablas/sge_puntaje.sql',
            $dir.'/20_Tablas/sge_puntaje_aplicacion.sql',
            $dir.'/20_Tablas/sge_puntaje_pregunta.sql',
            $dir.'/20_Tablas/sge_puntaje_respuesta.sql',
            $dir.'/30_Checks/ck_sge_evaluacion_cerrada.sql',
            $dir.'/40_Indices/ix_evaluacion_formhab_formhabdet_puntaje.sql',            
            $dir.'/50_SetVals/sge_evaluacion_setval.sql',
            $dir.'/50_SetVals/sge_puntaje_aplicacion_setval.sql',
            $dir.'/50_SetVals/sge_puntaje_pregunta_setval.sql',
            $dir.'/50_SetVals/sge_puntaje_respuesta_setval.sql',
            $dir.'/50_SetVals/sge_puntaje_setval.sql',
            $dir.'/60_FK/fk_sge_evaluacion_sge_habilitacion.sql',
            $dir.'/60_FK/fk_sge_puntaje_aplicacion_sge_evaluacion.sql',
            $dir.'/60_FK/fk_sge_puntaje_aplicacion_sge_formulario_habilitado.sql',
            $dir.'/60_FK/fk_sge_puntaje_aplicacion_sge_formulario_habilitado_detalle.sql',
            $dir.'/60_FK/fk_sge_puntaje_aplicacion_sge_puntaje.sql',
            $dir.'/60_FK/fk_sge_puntaje_pregunta_sge_encuesta_definicion.sql',
            $dir.'/60_FK/fk_sge_puntaje_pregunta_sge_pregunta.sql',
            $dir.'/60_FK/fk_sge_puntaje_pregunta_sge_puntaje.sql',
            $dir.'/60_FK/fk_sge_puntaje_respuesta_sge_pregunta.sql',
            $dir.'/60_FK/fk_sge_puntaje_respuesta_sge_puntaje_pregunta.sql',
            $dir.'/60_FK/fk_sge_puntaje_respuesta_sge_respuesta.sql',
            $dir.'/60_FK/fk_sge_puntaje_sge_encuesta_atributo.sql',
            $dir.'/70_Permisos/grant_sge_evaluacion.sql',
            $dir.'/70_Permisos/grant_sge_puntaje.sql',
            $dir.'/70_Permisos/grant_sge_puntaje_aplicacion.sql',
            $dir.'/70_Permisos/grant_sge_puntaje_pregunta.sql',
            $dir.'/70_Permisos/grant_sge_puntaje_respuesta.sql',
        );

        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }    
    
    function negocio__680()
    {
        $dir = $this->get_dir_ddl();
        $archivos = array(
            $dir.'80_Procesos/150_ws_resultados_de_encuesta_detalle.sql',
        );  
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }

    //este método de negocio también cubre el ticket: negocio__738
    function negocio__727()
    {
        $dir = $this->get_dir_ddl();
        $archivos = array(
            $dir.'80_Procesos/190_copiar_encuesta_a_unidad_gestion.sql',
        );  
        
        foreach ($archivos as $archivo) {
            $this->get_db()->ejecutar_archivo($archivo);    
        }
    }
    
    /*
     * Recorre todas las encuestas existentes y setea para cada una el campo implementada
     * igual a TRUE en caso de que la encuesta este en uso en alguna habilitación vigente
     */
    function negocio__728()
    {
        $encuestas = $this->get_encuestas();
        foreach ($encuestas as $key => $value) {
            if ($this->esta_en_uso($value['encuesta'])) {
                $this->update_implementada($value['encuesta']);
            }
        }
    }
    
    function get_encuestas()
	{
		$sql = 'SELECT	sge_encuesta_atributo.encuesta,
                        sge_encuesta_atributo.nombre,
                        sge_encuesta_atributo.descripcion,
                        sge_encuesta_atributo.texto_preliminar,
                        sge_encuesta_atributo.implementada,
                        sge_encuesta_atributo.estado,
                        sge_encuesta_atributo.unidad_gestion
				FROM	sge_encuesta_atributo';
        
        return $this->get_db()->consultar($sql);
	}
    
    function esta_en_uso($encuesta)
    {
        //Si está en uso en alguna habilitación con fecha desde menor o igual a hoy entonces deberá actualizar su estado a implementada
        $encuesta = $this->get_db()->quote($encuesta);
        $sql = "SELECT	COUNT(DISTINCT sge_formulario_habilitado_detalle.encuesta)
                FROM 	sge_formulario_habilitado_detalle
                            INNER JOIN sge_formulario_habilitado ON (sge_formulario_habilitado_detalle.formulario_habilitado = sge_formulario_habilitado.formulario_habilitado)
                            INNER JOIN sge_habilitacion ON (sge_formulario_habilitado.habilitacion = sge_habilitacion.habilitacion)
                WHERE 	sge_formulario_habilitado_detalle.encuesta = $encuesta
                AND     sge_habilitacion.fecha_desde <= current_date";
        
		$res = $this->get_db()->consultar_fila($sql);
        
		if ($res['count'] > 0) {
			return true;
		} else {
            return false;
        }
    }
    
    function update_implementada($encuesta)
	{
		$encuesta = $this->get_db()->quote($encuesta);
		
		$sql = "UPDATE	sge_encuesta_atributo
        		SET		implementada = 'S'
        		WHERE   encuesta = $encuesta";
        
        $this->get_db()->ejecutar($sql);
	}
    
}