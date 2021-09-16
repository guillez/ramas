<?php

class co_formularios_respondidos
{
    function get_pdf($form_hab_externo, $codigo_externo, $habilitacion, $unidad_gestion)
    {
        $datos = $this->get_formulario_habilitado($form_hab_externo, $habilitacion, $unidad_gestion);

        if (isset($datos['formulario_habilitado'])) {
            $formulario_habilitado = $datos['formulario_habilitado'];
        } else {
            return false;
        }

        $datos = $this->get_respondido_formulario($codigo_externo, $formulario_habilitado);
        if (isset($datos['respondido_formulario'])) {
            $respondido_formulario = $datos['respondido_formulario'];
        } else {
            return false;
        }
        
        if (isset($formulario_habilitado) && isset($respondido_formulario)) {
            
            $builder = new builder_pdf();
            $config  = new formulario_controlador_config($formulario_habilitado, $respondido_formulario);
            $formulario_controlador = new formulario_controlador();

            $config->set_vista_builder($builder);
            $formulario_controlador->set_configuracion($config);
            $formulario_controlador->procesar_request();
        } else {
            return false;
        }
    }
    
    function get_formulario_habilitado($form_hab_externo, $habilitacion, $unidad_gestion)
    {
        $form_hab_externo = kolla_db::quote($form_hab_externo);
        $habilitacion = kolla_db::quote($habilitacion);
        $unidad_gestion = kolla_db::quote($unidad_gestion);
        
		$sql = "SELECT  sge_formulario_habilitado.formulario_habilitado
                FROM    sge_formulario_habilitado 
                      INNER JOIN sge_habilitacion ON (sge_habilitacion.habilitacion = sge_formulario_habilitado.habilitacion)
                WHERE   sge_formulario_habilitado.formulario_habilitado_externo = $form_hab_externo
                        AND sge_formulario_habilitado.habilitacion = $habilitacion
                        AND sge_habilitacion.unidad_gestion = $unidad_gestion";
                        
		return kolla_db::consultar_fila($sql);
    }
    
    function get_respondido_formulario($codigo_externo, $formulario_habilitado)
    {
        $codigo_externo        = kolla_db::quote($codigo_externo);
        $formulario_habilitado = kolla_db::quote($formulario_habilitado);
        
		$sql = "SELECT  sge_respondido_encuestado.respondido_formulario
                FROM    sge_respondido_encuestado
                WHERE   sge_respondido_encuestado.codigo_externo = $codigo_externo
                AND     sge_respondido_encuestado.formulario_habilitado = $formulario_habilitado";
                        
		return kolla_db::consultar_fila($sql);
    }

}