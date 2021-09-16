<?php 

class ci_navegacion_por_ug_reportes extends ci_navegacion_por_ug
{
    /**
     * Retorna las habilitaciones para el combo de acuerdo a
     * la Unidad de Gestión seteada y si esta archivada o no
     */
    function get_habilitaciones($archivadas = false)
    {
        $this->set_ug();
        return toba::consulta_php('consultas_habilitaciones')->get_habilitaciones_combo_por_ug_y_archivada($this->s__ug, $archivadas);
    }
    
    //-----------------------------------------------------------------------------------
    //---- Auxiliares -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    
    function ajax__get_habilitaciones($archivadas, toba_ajax_respuesta $respuesta)
	{
        $habilitaciones = $this->get_habilitaciones($archivadas);
        $parametro_respuesta[0][] = 'nopar';
        $parametro_respuesta[0][] = $this->get_mensaje_seleccione();
        $indice = 1;
        
        foreach ($habilitaciones as $habilitacion) {
            $parametro_respuesta[$indice][] = $habilitacion['habilitacion'];
            $parametro_respuesta[$indice][] = $habilitacion['desc_id_rango'];
            $indice++;
        }
        
		$estructura = array('respuesta' => $parametro_respuesta);
		$respuesta->set($estructura);
	}
    
    function get_mensaje_seleccione()
    {
        return '--- Seleccionar ---';
    }
    
}
?>