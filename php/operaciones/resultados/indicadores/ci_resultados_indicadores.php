<?php
class ci_resultados_indicadores extends toba_ci
{
	protected $s__filtro;
	protected $s__indicadores;
	protected $s__datos_reporte;
	
	//-----------------------------------------------------------------------------------
	//---- Configuraciones --------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf() 
	{
		if (isset($this->s__filtro)) {
			$formulario = $this->s__filtro['formulario_habilitado'];
            $habilitacion = $this->s__filtro['habilitacion'];
			$resultado = toba::consulta_php('consultas_indicadores')->get_formulario_indicadores_definidos($formulario, $habilitacion);
			$this->s__indicadores = $resultado;
            
			if (empty($this->s__indicadores)) {
				$this->agregar_notificacion('No hay indicadores definidos.');
			}
		}
	}
	
	function resetear() 
	{
		unset($this->s__filtro);
		unset($this->s__indicadores);
		
		$this->dep('indicador1')->destruir();
		$this->dep('indicador2')->destruir();
		$this->dep('indicador3')->destruir();
		$this->dep('indicador4')->destruir();
		$this->dep('indicador5')->destruir();						
	}
	
	//-----------------------------------------------------------------------------------
	//---- filtro -----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__filtro(toba_ei_formulario $form)
	{
		if (isset($this->s__filtro)) {
			return $this->s__filtro;
		}
	}
	
	function evt__filtro__filtrar($filtro)
	{
        /*
		$resultado = toba::consulta_php('consultas_encuestas')->get_datos_habilitacion($filtro['habilitacion']);
        
		if (isset($filtro['fecha_desde'])) {
			$ffdesde = mktime(0,0,0,substr($filtro['fecha_desde'],5,2),substr($filtro['fecha_desde'],8,2),substr($filtro['fecha_desde'],0,4));
		}
		if (isset($filtro['fecha_hasta'])) {
			$ffhasta = mktime(0,0,0,substr($filtro['fecha_hasta'],5,2),substr($filtro['fecha_hasta'],8,2),substr($filtro['fecha_hasta'],0,4));
		}
		
		$hfdesde = mktime(0,0,0,substr($resultado[0]['fecha_desde'],5,2),substr($resultado[0]['fecha_desde'],8,2),substr($resultado[0]['fecha_desde'],0,4));
		$hfhasta = mktime(0,0,0,substr($resultado[0]['fecha_hasta'],5,2),substr($resultado[0]['fecha_hasta'],8,2),substr($resultado[0]['fecha_hasta'],0,4));
		$filtrar = false;
		
		if (isset($ffdesde) && isset($ffhasta)) {
			$filtrar = (($hfdesde <= $ffdesde) && ($hfhasta >= $ffhasta) && ($ffdesde <= $ffhasta));
		} else { 
			if (isset($ffdesde)) {
				$filtrar = (($hfdesde <= $ffdesde) && ($ffdesde <= $hfhasta));
			} else {
				if (isset($ffhasta)) {
					$filtrar = (($hfhasta >= $ffhasta) && ($ffhasta >= $hfdesde));
				} else {
					$filtrar = true;
				}
			}
		}
		
		if ($filtrar) {
			$this->s__filtro = $filtro;
		} else {
			unset($this->s__filtro);
			toba::notificacion()->agregar('Verifique que las fechas estén dentro del rango de la habilitación.', 'info');
		}
         */
	}
	
	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

	//-----------------------------------------------------------------------------------
	//---- indicador1 -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__indicador1(toba_ei_cuadro $cuadro)
	{		
		if (isset($this->s__filtro) & isset($this->s__indicadores) & isset($this->s__indicadores[0])) {
			$this->cargar_cuadro_indicador($cuadro,$this->s__indicadores[0]);
		} 
	}
	
	//-----------------------------------------------------------------------------------
	//---- indicador2 -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__indicador2(toba_ei_cuadro $cuadro)
	{		
		if (isset($this->s__filtro) & isset($this->s__indicadores) & isset($this->s__indicadores[1])) {
			$this->cargar_cuadro_indicador($cuadro,$this->s__indicadores[1]);
		} 
	}
	
	//-----------------------------------------------------------------------------------
	//---- indicador3 -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__indicador3(toba_ei_cuadro $cuadro)
	{		
		if (isset($this->s__filtro) & isset($this->s__indicadores) & isset($this->s__indicadores[2])) {
			$this->cargar_cuadro_indicador($cuadro,$this->s__indicadores[2]);
		} 
	}

	//-----------------------------------------------------------------------------------
	//---- indicador4 -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__indicador4(toba_ei_cuadro $cuadro)
	{			
		if (isset($this->s__filtro) & isset($this->s__indicadores) & isset($this->s__indicadores[3])) {
			$this->cargar_cuadro_indicador($cuadro,$this->s__indicadores[3]);
		} 
	}	
	
	//-----------------------------------------------------------------------------------
	//---- indicador5 -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__indicador5(toba_ei_cuadro $cuadro)
	{
		if (isset($this->s__filtro) & isset($this->s__indicadores) & isset($this->s__indicadores[4])) {
			$this->cargar_cuadro_indicador($cuadro,$this->s__indicadores[4]);
		} 
	}
	
	function cargar_cuadro_indicador(toba_ei_cuadro $cuadro, $indicador)
	{	
		$trnumero = $this->s__datos_reporte['total_rtas_hab'];
		$srnumero = 0;
		$srporcentaje = 100;		
		
		if ($trnumero > 0) {
			//obtener la cantidad de encuestas realizadas en las que no se respondió este indicador
			//rever la consulta que se hace a continuación para que maneje los casos de preguntas abiertas
			$sr = toba::consulta_php('consultas_indicadores')->get_resultados_indicador_sin_responder($indicador, $this->s__filtro);
			$srnumero = $sr[0]['sinresponder'];
			$srporcentaje = round($sr[0]['sinresponder'] * 100 / $trnumero, 2);
		}
		
		$irnumero = $trnumero - $srnumero;
		$sininfo = array('respuesta_nombre' => 'Sin información del encuestado', 'cantidad' => $srnumero, 'porcentajeh' => $srporcentaje."%", 'porcentajei' => '--');
		$infototal = array('respuesta_nombre' => 'Total de respuestas al indicador', 'cantidad' => $irnumero, 'porcentajeh' => '--', 'porcentajei' => '--');
				
		//obtener las opciones de respuesta del indicador con la cantidad de veces que fue elegido
		$resultado = toba::consulta_php('consultas_indicadores')->get_resultados_indicadores_agrupados($indicador, $this->s__filtro);
		$cuadro->set_titulo($indicador['pregunta_nombre']);
		$cuadro->set_descripcion($indicador['componente_descripcion']);
		$cuadro->set_modo_descripcion(false);
		
		if (!empty($resultado)) {
			$cant = sizeof($resultado);
			$subtotal = 0;
			for ($i = 0; $i < $cant; $i++) {
				$resultado[$i]['porcentajeh'] = round($resultado[$i]['cantidad'] * 100 / $trnumero, 2) . "%";
				$subtotal += $resultado[$i]['cantidad']; 
			}
			
			for ($i = 0; $i < $cant; $i++) {
				$resultado[$i]['porcentajei'] = round($resultado[$i]['cantidad'] * 100 / $subtotal, 2) . "%";
			}
		}
		
		//si es de respuesta múltiple se muestra la cantidad de respuestas totales obtenidas
		$de_multiple = array(4, 5); //list y check
		if (in_array($indicador['componente'], $de_multiple)) {
			$infototal = array('respuesta_nombre' => 'Total de respuestas al indicador', 'cantidad' => $subtotal, 'porcentajeh' => '--', 'porcentajei' => '--');
		}

		$resultado[] = $sininfo;
		$resultado[] = $infototal;
		$cuadro->set_datos($resultado);
	}
	
	//-----------------------------------------------------------------------------------
	//---- form_resumen -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	function conf__form_resumen(toba_ei_formulario $form)
	{
		//obtener el total de veces que fue respondido el formulario segun el filtro aplicado
		if (isset($this->s__filtro)) {
			$tr = toba::consulta_php('consultas_indicadores')->get_total_de_respuestas_encuesta($this->s__filtro);
            if (!empty($tr)) {
                $this->s__datos_reporte['total_rtas_hab'] = $tr[0]['respondieron'];
                return array('total' => $this->s__datos_reporte['total_rtas_hab']);
            }
		} else {
			return array('total' => '--');
		}
	}

}
?>