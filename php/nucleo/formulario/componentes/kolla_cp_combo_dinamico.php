<?php

/**
 * Esta clase crea un combo vacío para usar en conjunto con el popup de localidad.
 * De esta manera, se completa dicho combo dinámicamente de acuerdo a la localidad
 * seleccionada. Este tipo de componente no es visible al usuario al momento de
 * crear una pregunta.
 *
 * @author Germán
 */

class kolla_cp_combo_dinamico extends kolla_cp_combo
{
	function get_html($id, $respuestas, $obligatoria, $solo_lectura = false, $localidad = '')
	{
        if ($localidad == '') {
            return parent::get_html($id, array(), $obligatoria);
        }
        
        $respuesta = $this->get_respuesta_combo($respuestas);
        $codigos_postales = toba::consulta_php('consultas_mug')->get_codigo_postal($localidad);
        
        if (!empty($codigos_postales)) {
            foreach ($codigos_postales as $key => $value) {
                $cp[$key]['respuesta']       =  $value['id'];
                $cp[$key]['respuesta_valor'] =  $value['codigo_postal'];
                
                if ($value['id'] == $respuesta) {
                    $cp[$key]['sel'] =  'S';
                }
            }
        }
        
		return parent::get_html($id, $cp, $obligatoria);
	}
    
    function get_respuesta_combo($respuestas)
    {
        foreach ($respuestas as $key => $value) {
            if (isset($value['sel']) && $value['sel'] == 'S') {
                return $value['respuesta'];
            }
        }
        
        return null;
    }


    function get_pdf($respuestas, $imprimir_respuestas_completas = false, $respuestas_diferidas = null, $completar_impreso = true)
	{
        $pdf = $this->get_pre_pdf();
        
        foreach ($respuestas as $respuesta) {
            if (isset($respuesta['sel']) && $respuesta['sel'] == 'S') {
                $pdf .= $respuesta['respuesta_valor'];
            }
        }
        
        $pdf .= $this->get_post_pdf();
		return $pdf;
	}
    
}
?>