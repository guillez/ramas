<?php
class ci_abm_encuestas_indicadores extends toba_ci
{
	protected $s__filtro;
	protected $s__seleccion;
	protected $s__indicadores;
	protected $s__encuesta;

	function resetear()
	{
		unset($this->s__indicadores);
		unset($this->s__encuesta);
		$this->tabla()->resetear();
		$this->set_pantalla('seleccion');
	}
	
	function tabla()
	{
		return $this->dep('indicador');
	}

	//---- filtro -----------------------------------------------------------------------	
	
	function evt__filtro__filtrar($filtro)
	{
		$this->s__filtro = $filtro;
	}

	function conf__filtro()
	{
		if (isset($this->s__filtro)) {
			return $this->s__filtro;
		}
	}

	function evt__filtro__cancelar()
	{
		unset($this->s__filtro);
	}

	//---- cuadro -----------------------------------------------------------------------
	
	function conf__cuadro(toba_ei_cuadro $cuadro)
	{
		if ( isset($this->s__filtro) ) {
			//$datos = toba::consulta_php('consultas_encuestas')->get_encuestas_formulario_habilitado($this->s__filtro['formulario_habilitado']);
            $datos = toba::consulta_php('consultas_encuestas')->get_encuestas_habilitacion($this->s__filtro['habilitacion']);
            $cuadro->set_datos($datos);
		}
	}

	function evt__cuadro__seleccion($seleccion)
	{
		$this->s__seleccion = $seleccion;
		$this->set_pantalla('edicion');
	}
	
	//---- encuesta ---------------------------------------------------------------------

	function conf__encuesta(toba_ei_formulario $form)
	{
		if (!isset($this->s__encuesta)) {
			//$this->s__encuesta = toba::consulta_php('consultas_encuestas')->get_encuesta_x_habilitacion($this->s__seleccion['formulario_habilitado_detalle']);
            $this->s__encuesta = $this->s__seleccion['encuesta'];
            toba::consulta_php('consultas_encuestas')->get_encuesta_x_habilitacion($this->s__encuesta);
		}
		
		$form->set_datos($this->s__encuesta);
	}

	//---- form_indicadores -------------------------------------------------------------

	function conf__form(toba_ei_formulario $form)
	{
		if (isset($this->s__indicadores)) {
			$form->set_datos($this->s__indicadores);
			unset($this->s__indicadores);
		} else {
			$this->tabla()->cargar($this->s__seleccion);
			$indicadores = $this->tabla()->get_filas();
			$datos = array();
			
			foreach ($indicadores as $indicador) {
				$ed = kolla_db::quote($indicador['encuesta_definicion']);
				$sql = "SELECT	bloque
						FROM	sge_encuesta_definicion
						WHERE	encuesta_definicion = $ed
						";
				
				$def = kolla_db::consultar_fila($sql);
				
				$datos[] = array(
									'bloque'				=> $def['bloque'],
									'encuesta_definicion'	=> $indicador['encuesta_definicion']
								);
			}
			
			$datos = $this->completar_indicadores_nulos($datos);
			$form->set_datos($datos);

		}
	}

	/*
	 * Retorna un array de 5 elementos para el formulario multilinea
	 */
	function completar_indicadores_nulos($datos)
	{
		$faltan = 5 - count($datos);
		
		if ($faltan > 0) {
			$indicador_nulo = array(
										'bloque' 			  => null,
										'encuesta_definicion' => null
									);
			for ($i = 1; $i <= $faltan; $i++) {
				array_push($datos, $indicador_nulo);
			}
		}
		
		return $datos;
	}
	
	function evt__form__modificacion($datos)
	{
		$this->s__indicadores = $datos;
	}
	
	function get_preguntas_para_indicador($bloque)
	{  
		return toba::consulta_php('consultas_indicadores')->get_preguntas_para_indicador($this->s__seleccion['encuesta'], $bloque);
	}
	
	//---- Eventos ----------------------------------------------------------------------

	function evt__cancelar()
	{
		$this->resetear();
	}

	function evt__eliminar()
	{
		$this->tabla()->eliminar_filas();
		$this->tabla()->sincronizar();
		$this->resetear();
	}

	function evt__guardar()
	{
		if (!$this->existen_indicadores_definidos()) {
			$mensaje_error = $this->get_mensaje('control_indicadores_vacio');
			throw new toba_error($mensaje_error);
		}
		
		$this->tabla()->eliminar_filas();
		
		foreach ($this->get_indicadores() as $indicador) {
			$fila = array(
							'encuesta_definicion'			=> $indicador['encuesta_definicion'],
							'formulario_habilitado_detalle' => $this->s__seleccion['formulario_habilitado_detalle'],
							'formulario_habilitado'			=> $this->s__filtro['formulario_habilitado']
						 );
			$this->tabla()->nueva_fila($fila);
		}
		
		try {
			$this->tabla()->sincronizar();
		} catch (toba_error_db $e) {
			throw new toba_error('No es posible seleccionar una misma pregunta para un mismo bloque.');
		}
		
		$this->resetear();
	}
	
	/*
	 * Retorna true en caso de tener al menos un indicador deinido, y false en caso contrario
	 */
	function existen_indicadores_definidos()
	{
		foreach ($this->s__indicadores as $indicador) {
			if (isset($indicador['bloque'])) {
				return true;
			}
		}
		
		return false;
	}
	
	/*
	 * Retorna aquellas filas que tienen valores distintos de null
	 */
	function get_indicadores()
	{
		$indicadores = array();
		foreach ($this->s__indicadores as $indicador) {
			if (isset($indicador['bloque'])) {
				array_push($indicadores, $indicador);
			}
		}
		
		return $indicadores;
	}
	
	//---- Auxiliares -------------------------------------------------------------------
	
	function get_bloques_encuesta_combo()
	{
		//$fhd = kolla_db::quote($this->s__seleccion['formulario_habilitado_detalle']);
		/*
		$sql = "SELECT		b.bloque,
							b.nombre
				FROM		sge_formulario_habilitado_detalle AS fhd
								INNER JOIN sge_encuesta_definicion AS ed ON (fhd.encuesta = ed.encuesta)
								INNER JOIN sge_bloque AS b ON (ed.bloque = b.bloque)
				WHERE		fhd.formulario_habilitado_detalle = $fhd
				ORDER BY	b.orden
				";
		*/
        $e = kolla_db::quote($this->s__seleccion['encuesta']);
        $sql = "SELECT DISTINCT	b.bloque,
                            b.orden,
							b.nombre
				FROM		sge_formulario_habilitado_detalle AS fhd
								INNER JOIN sge_encuesta_definicion AS ed ON (fhd.encuesta = ed.encuesta)
								INNER JOIN sge_bloque AS b ON (ed.bloque = b.bloque)
				WHERE		ed.encuesta = $e
				ORDER BY	b.orden
				";
		return kolla_db::consultar($sql);
	}
	
	function get_preguntas_para_combo($bloque)
	{
		$bloque = kolla_db::quote($bloque);
		
		$sql = "SELECT		sge_encuesta_definicion.encuesta_definicion,
							sge_encuesta_definicion.pregunta,
							CASE WHEN LENGTH(sge_pregunta.nombre) >= 80 
								THEN SUBSTR(sge_pregunta.nombre, 0, 80) || ' ...  - ' || sge_componente_pregunta.descripcion  
								ELSE sge_pregunta.nombre || ' - ' || sge_componente_pregunta.descripcion
							END AS nombre
				FROM		sge_encuesta_definicion
								INNER JOIN sge_pregunta ON (sge_encuesta_definicion.pregunta = sge_pregunta.pregunta)
								INNER JOIN sge_componente_pregunta ON (sge_pregunta.componente_numero = sge_componente_pregunta.numero)
				WHERE		sge_encuesta_definicion.bloque = $bloque
				ORDER BY	sge_encuesta_definicion.orden
				";
		
		return kolla_db::consultar($sql);
	}

}
?>