<?php
class ci_copiar_o_mover_encuesta extends ci_navegacion
{
    //-----------------------------------------------------------------------------------
	//---- PANTALLA INICIAL -------------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
	//---- formulario -------------------------------------------------------------------
	
	function evt__formulario__modificacion($datos)
	{
        if (isset($datos['unidad_gestion_destino_mover'])) {
            try {
                act_encuestas::mover_encuesta($datos['encuesta'], $datos['unidad_gestion_destino_mover']);
                toba::notificacion()->info('La encuesta se movió correctamente.');
            } catch (toba_error_db $e) {
                toba::logger()->error($e->getMessage());
				throw new toba_error($e->getMessage());
			}
        } else {
            $unidades_gestion = $datos['unidad_gestion_destino_copiar'];
            try {
                foreach($unidades_gestion as $unidad_gestion) {
                    act_encuestas::copiar_encuesta($datos['encuesta'], $unidad_gestion);
                }
                
                if (count($unidades_gestion) == 1) {
                    toba::notificacion()->info('La encuesta se copió correctamente.');
                } else {
                    toba::notificacion()->info('Las encuestas se copiaron correctamente.');
                }
            } catch (toba_error_db $e) {
                toba::logger()->error($e->getMessage());
				throw new toba_error($e->getMessage());
            }
        }
	}
    
    //-----------------------------------------------------------------------------------
	//---- Encuestas para el combo ------------------------------------------------------
	//-----------------------------------------------------------------------------------
    
    function get_encuestas_por_accion_y_ug($accion = null, $ug = null)
	{
        if ($accion == 'C') {
            return toba::consulta_php('consultas_encuestas')->get_encuestas_por_ug_copiar($ug);
        }
		
        $encuestas = toba::consulta_php('consultas_encuestas')->get_encuestas_por_ug_mover($ug);
        
        foreach ($encuestas as $clave => $valor) {
            if ($this->validar_estructura_encuesta($valor['encuesta'], $ug)) {
                unset($encuestas[$clave]);
            }
        }
        
        return $encuestas;
	}
    
    function validar_estructura_encuesta($encuesta, $unidad_gestion)
    {
        $estructura = toba::consulta_php('consultas_encuestas')->get_preguntas_encuesta($encuesta);
        
        foreach ($estructura as $pregunta) {
            if (toba::consulta_php('consultas_encuestas')->es_pregunta_usada_o_predefinida($pregunta['pregunta'], $pregunta['comp_numero'], $encuesta, $unidad_gestion)) {
                return true;
            }
        }
        
        return false;
    }
    
	//-----------------------------------------------------------------------------------
	//---- JAVASCRIPT -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function extender_objeto_js()
	{
        echo "
		
        //---- Eventos ---------------------------------------------
		
		{$this->objeto_js}.evt__ejecutar = function()
		{
            var accion = this.dep('formulario').ef('accion').get_estado();
            
            if (accion == 'M') {
                var mensaje = 'Esta a punto de mover una encuesta de Unidad de Gestión ¿Desea continuar?';
            } else {
                var mensaje = 'Esta a punto de copiar una encuesta a otra(s) Unidad(es) de Gestión ¿Desea continuar?';
            }
            
            return confirm(mensaje);
		}
		";
	}

}
?>