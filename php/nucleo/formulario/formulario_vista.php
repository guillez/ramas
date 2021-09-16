<?php

/**
 * Este es el director de la vista (patrón Builder). Se encarga de recorrer la
 * estructura del formulario, y delega en otro objeto la creación concreta de la
 * vista (vista_builder).
 */
class formulario_vista
{
	protected $formulario;
	protected $url_post;
	protected $url_base_fotos ;
	protected $id_elemento_actual; //parametro global
	protected $puede_guardar = false;
	public $builder;
	protected $texto_preliminar = null;
	protected $imprimir_respuestas_completas = false;
	protected $mostrar_aviso_guardado = false;
	protected $mostrar_progreso = false;

	/**
	 * El builder determina la salida de la vista.
	 * @param vista_builder $builder
	 */
	function formulario_vista(vista_builder $builder)
	{
		$this->builder = $builder;
	}

	function set_url_post($url)
	{
		$this->url_post = $url;
	}

	function set_url_base_fotos($url)
	{
		$this->url_base_fotos = $url;
	}

	function set_puede_guardar($puede)
	{
		$this->puede_guardar = $puede;
	}

	function set_formulario(formulario $form)
	{
		$this->formulario = $form;
	}

	function set_texto_preliminar($html)
	{
		$this->texto_preliminar = $html;
	}

	function set_imprimir_respuestas_completas($imprimir_respuestas_completas)
	{
		$this->imprimir_respuestas_completas = $imprimir_respuestas_completas;
	}
        
    function set_mostrar_aviso_guardado($mostrar_aviso_guardado)
	{
        $this->mostrar_aviso_guardado = $mostrar_aviso_guardado;
	}

	function set_mostrar_progreso($boolean)
	{
		$this->mostrar_progreso = $boolean;
	}

	function generar_interface()
	{
		$x = $this->formulario->get_datos();
		$this->builder->crear_encabezado_formulario($x[0]['formulario'], $this->texto_preliminar, $this->url_post, $this->puede_guardar);
                
        if ($this->mostrar_aviso_guardado) { 
            $this->builder->mostrar_aviso_guardado(); 
        }
        
		$this->generar_encuestas();
        
        if ($this->mostrar_progreso) {
            $this->builder->crear_barra_progreso();
        }
        
		$this->builder->crear_cierre_formulario();
	}

	protected function mostrar_mensajes()
	{
        $msg = $this->formulario->get_mensajes();
		
        if ($msg == null) {
            return;
        }
        
        foreach ($msg as $mg) //para los errores de cada fila
            $this->builder->crear_mensaje ($mg);
		
	}

	protected function generar_encuestas()
	{
		foreach($this->formulario->get_datos() as $encuesta) {
			$e_nombre 		  = $encuesta['encuesta']['nombre'];
			$e_id 			  = $encuesta['encuesta']['id'];
			$elemento 		  = $encuesta['elemento']['elemento'];
			$texto_preliminar = $encuesta['encuesta']['texto_preliminar'];
			$elemento_desc 	  = isset($encuesta['elemento']['elemento_descripcion'])?$encuesta['elemento']['elemento_descripcion'] : null;
			$elemento_img 	  = isset($encuesta['elemento']['elemento_img'])?$encuesta['elemento']['elemento_img'] : null;
			if ($elemento_img != null && $this->url_base_fotos) {
				$elemento_img = $this->url_base_fotos . $elemento_img;
			}
			$this->id_elemento_actual = $elemento;
			$this->builder->crear_encabezado_encuesta($e_id, $e_nombre,$encuesta['bloques'], $texto_preliminar);
			$this->builder->crear_elemento($elemento_desc, $elemento_img);
			$this->generar_bloques($encuesta['bloques']);
			$this->builder->crear_cierre_encuesta();
			
		}
	}

	protected function generar_bloques($bloques)
	{
		foreach ($bloques as $bloque) {
			$id_bloque = 'b'. $this->id_elemento_actual. '_'. $bloque['bloque'];
			$this->builder->crear_encabezado_bloque($id_bloque, $bloque['nombre']);
			$this->generar_preguntas($bloque['preguntas']);
			$this->builder->crear_cierre_bloque();
		}
	}

	protected function generar_preguntas($preguntas)
	{
		foreach ($preguntas as $pregunta) {
			$id = formulario::get_id_label_componente($this->id_elemento_actual, $pregunta);
			$obligatoria = isset($pregunta['obligatoria']) && $pregunta['obligatoria'] == 'S';
			$error_msg = isset($pregunta['error'])? $pregunta['error']:'';
			$nombre = $pregunta['pregunta_nombre'];

			/*
			if ($pregunta['componente'] == 'label') {
				$this->builder->   crear_componente_label($id, $pregunta['pregunta_nombre']);
				continue;
			}
            
			$componenteid = formulario::get_id_componente($this->id_elemento_actual, $pregunta);
            $this->builder->crear_encabezado_pregunta($id, $componenteid, $obligatoria, $nombre, $pregunta['ayuda'] ,isset($pregunta['error']), $pregunta['componente']);
            $this->generar_respuesta($pregunta, $obligatoria, $componenteid);
            $this->builder->crear_error_label($error_msg);
            $this->builder->crear_cierre_pregunta();
			*/

			if (repositorio_etiquetas::instance()->es_etiqueta($pregunta['componente'])) {
				repositorio_etiquetas::instance()->crear_salida($this->builder, $pregunta['componente'], $id, $pregunta['pregunta_nombre']);
				continue;
			}

            if ($pregunta['componente'] == 'label') {
                $this->builder->crear_componente_label($id, $pregunta['pregunta_nombre']);
            } else {
                $componenteid = formulario::get_id_componente($this->id_elemento_actual, $pregunta);
                $this->builder->crear_encabezado_pregunta($id, $componenteid, $obligatoria, $nombre, $pregunta['ayuda'] ,isset($pregunta['error']), $pregunta['componente']);
                $this->generar_respuesta($pregunta, $obligatoria, $componenteid);
                $this->builder->crear_error_label($error_msg);
                $this->builder->crear_cierre_pregunta();
			}
		}
	}

	protected function generar_respuesta($pregunta, $obligatoria, $componenteid)
	{
		$opciones_respuesta = $pregunta['respuestas'];
		$componente = $pregunta['componente'];
		$componente_html = repositorio_componentes::get_componente($componente);

        if (isset($componente_html)) {
			$this->builder->crear_respuesta($componente_html, $componenteid, $opciones_respuesta, $obligatoria, $pregunta['pregunta'], $this->imprimir_respuestas_completas/*, $es_compuesta*/);
		}
	}

}
?>
