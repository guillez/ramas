<?php

abstract class kolla_comp_opciones extends kolla_comp_encuesta
{
	protected $obligatorio; 
	
	function __construct($validador, $atributos, $clases)
	{
		parent::__construct($validador, $atributos, $clases);
	}
	
    abstract function get_pre($id);
	abstract function get_opcion($id, $clave, $valor, $seleccionada);
	abstract function get_post($id);

	function get_html($id, $respuestas, $obligatoria)
	{
		$this->obligatorio = $obligatoria;
		$html =  $this->get_pre($id).'<div class="container-fluid">'.'<div class="row">';
        $es_radio_o_check = $this instanceof kolla_cp_radio || $this instanceof kolla_cp_check;
                
        if ($es_radio_o_check) {
            $id_arreglo = explode('_', $id);
            $pregunta = toba::consulta_php('consultas_encuestas')->get_pregunta_encuesta_definicion($id_arreglo[2]);
        }
        
		foreach($respuestas as $respuesta) {
			$clave = $respuesta['respuesta'];
			$valor = $respuesta['respuesta_valor'];
			$seleccionada = (isset($respuesta['sel']) && $respuesta['sel'] == 'S');
            
            if ($es_radio_o_check) {
                $html.= $this->get_opcion($id, $clave, $valor, $seleccionada, $pregunta['visualizacion_horizontal']);
            } else {
                $html.= $this->get_opcion($id, $clave, $valor, $seleccionada);
            }
		}
        
		$html .= '</div>'.'</div>'.$this->get_post($id);
		return $html;
	}
	
	public function get_pre_pdf($completar_impreso = true)
	{
		return parent::get_pre_pdf($completar_impreso).'<table>';
	}
	
	public function get_post_pdf()
	{
		return '</table>'.parent::get_post_pdf();
	}
	
	protected function get_imagen()
	{
		return '<img src="file://'.toba::proyecto()->get_path().'/www/img/radio_off.gif" height="10"/>';
	}
	
	protected function get_imagen_seleccionada()
	{
		return '<img src="file://'.toba::proyecto()->get_path().'/www/img/radio_on.gif" height="10" align="left"/>';
	}

    function get_pdf($respuestas, $imprimir_respuestas_completas = false, $respuestas_diferidas = null, $completar_impreso = true)
    {
        $pdf = $this->get_pre_pdf();
	
        $pdf .= isset($respuestas_diferidas) ? '<b>('.$respuestas_diferidas.")</b> " : '';

        if (!$imprimir_respuestas_completas) {
            //reducir el set de respuestas para mostrar solo las que fueron elegidas
            $respuestas = $this->quitar_no_elegidas($respuestas);
        }
        
        //Valores de formateo
        $indice_opcion	  = 1;
        $respuestas 	  = $this->reordenar_respuestas($respuestas);
        $ultima_respuesta = end($respuestas);

        //Salida pdf para los elementos tr y td 
        foreach($respuestas as $respuesta) {
            $seleccionada = (isset($respuesta['sel']) && $respuesta['sel'] == 'S');
            $imagen = $seleccionada ? $this->get_imagen_seleccionada() : $this->get_imagen();
            $incluir_colspan = $respuesta['respuesta'] == $ultima_respuesta['respuesta'];

            if ($indice_opcion == 1) {
                $tr_def  = '<tr style="line-height:10px;">';
                $colspan = $incluir_colspan ? 'colspan="3"' : '';
                $td_def	 = "<td $colspan>$imagen".$respuesta['respuesta_valor'].'</td>';
                $pdf    .= $tr_def.$td_def;
                $indice_opcion++;
                continue;
            }

            if ($indice_opcion == 2) {
                $colspan = $incluir_colspan ? 'colspan="2"' : '';
                $pdf    .= "<td $colspan>$imagen".$respuesta['respuesta_valor'].'</td>';
                $indice_opcion++;
                continue;
            }

            $pdf .= "<td>$imagen".$respuesta['respuesta_valor'].'</td></tr>';
            $indice_opcion = 1;
        }

        $pdf .= $indice_opcion != 1 ? '</tr>' : '';
		
        $pdf .= $this->get_post_pdf();
        return $pdf;
    }
	
	/*
	 * En caso de que dentro de las opciones se encuentren respuestas tales como otro/s,
	 * otra/s o No Responde y éstas no se encuentren al final las mueve hasta dicho lugar.
	 */
	function reordenar_respuestas($respuestas)
	{
		foreach ($respuestas as $clave => $respuesta) {
			$pasar_al_final = $this->contiene_valor_otro($respuesta['respuesta_valor']) || $this->contiene_valor_no_responde($respuesta['respuesta_valor']);
			
			if ($pasar_al_final) {
				$otro = $respuesta;
				unset($respuestas[$clave]);
				$respuestas[] = $otro;
				break;
			}
		}
		
		return $respuestas;
	}
        
    function quitar_no_elegidas($respuestas) 
    {
        $elegidas = array();
        foreach ($respuestas as $respuesta) {
            if (isset($respuesta['sel']) && $respuesta['sel'] == 'S') {
                $elegidas[] = $respuesta;
            }
        }
        return $elegidas;
    }
        
	
	function contiene_valor_otro($valor)
	{
		return (strncasecmp($valor, 'otro', 4) == 0) || (strncasecmp($valor, 'otra', 4) == 0);
	}
	
	function contiene_valor_no_responde($valor)
	{
		return (strcasecmp($valor, 'no responde') == 0);
	}
	
}
?>