<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of crear_builder
 *
 * @author alejandro
 */
interface vista_builder {
	
	/**
	 * Habilita el paginado, requiere de la lista de bloques por adelantado para
	 * generar las paginas en caso de que paginar sea true.
	 */
	public function set_paginacion($paginar, $lista_pags);
	
	/**
	 * Determina si la salida es editable si aplica (e.g, no aplica en pdf).
	 * @param type $boolean
	 */
	public function set_editable($boolean);
	
	/**
	 * Genera el head de la pagina, el body, contendor de pagina, menu si existe,etc, etc,
	 * y abre el formulario. El form tiene que hacer un post a la url, y el nombre generalmente
	 * indica el concepto del formulario.
	 */
	public function crear_encabezado_formulario($nombre_form, $texto_preliminar, $url_action_post, $puede_guardar);

    /**
     * Crea al final del formulario la barra de progreso que indica el porcentaje de avance
     * durante el llenado de la encuesta.
     */
	public function crear_barra_progreso();

	/**
	 * Cierra el html_del formulario. 
	 */
	public function crear_cierre_formulario();
	
	/**
	 * Abre el contenedor de la encuesta. 
	 */
	public function crear_encabezado_encuesta($id, $nombre,$bloques, $texto_preliminar=null);

	/**
	 *Cierra el contenedor de la encuesta. 
	 */
	public function crear_cierre_encuesta();
	
	/**
	 *Genera el contenedor de la pregunta y su etiqueta. Los parametros indican el
	 * id de la pregunta, si es obligatoria (booleano), el texto de la etiqueta y
	 * si tiene error (booleano) 
	 */
	public function crear_encabezado_pregunta($id, $for_id, $obligatoria, $texto,$ayuda ,$error);
	
	/**
	 *Cierra el contenedor de la pregunta. 
	 */
	public function crear_cierre_pregunta();
	
	/**
	 *Html valido (cerrado) del mensaje de error 
	 */
	public function crear_error_label($str);
	
	/**
	 *Html valido (cerrado) de las preguntas de tipo label. 
	 */
	public function crear_componente_label($id, $texto);

    public function crear_componente_subtitulo($id, $texto);
	public function crear_componente_titulo($id, $texto);
    public function crear_componente_texto_enriquecido($id, $texto);
	
	public function crear_respuesta(kolla_comp_encuesta $componente, $componenteid, $opciones_respuesta, $obligatoria, $id_pregunta, $imprimir_respuestas_completas);
	
	/**
	 *Html valido (cerrado) del elemento en evaluación. Los valores pueden ser
	 * nulos. La imagen es una url absoluta a la imagen. 
	 */
	public function crear_elemento($elemento_desc, $elemento_img);
	
	/**
	 *Abre el contenedor de un bloque. 
	 */
	public function crear_encabezado_bloque($id, $nombre);
	
	/**
	 *Cierra el contenedor de un bloque 
	 */
	public function crear_cierre_bloque();
	
	/**
	 * Mensajes de error/info/advertencia que genera el servidor al encuestado
	 */
	public function crear_mensaje($mensaje);

}

?>
