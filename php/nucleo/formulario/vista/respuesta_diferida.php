<?php
/**
 * Esta clase maneja aquellas respuestas con multiples opciones y que o bien poseen muchas opciones,
 * o bien la longitud promedio de cada una de ellas supera un maximo preestablecido, en su reemplazo
 * se coloca un indicador del tipo (1), (2), (3), ... y al final de la encuesta se detalla cada uno.
 */
class respuesta_diferida
{
	//Niveles de tolerancia para la cantidad de opciones en una respuesta y para la longitud de ellas (promedio)
	protected $cantidad_maxima_opciones_respuesta          = 15;
	protected $longitud_maxima_opcion_respuesta            = 60;
    protected $cantidad_maxima_opciones_respuesta_impresas = 100;
	
	protected $contador_diferidas;
	protected $lista_respuestas;
        
    protected $hay_eliminadas = false;
	
	public function __construct()
	{
        $this->contador_diferidas = 1;
        $this->lista_respuestas   = array();

        $sql = "SELECT valor 
                FROM kolla.sge_parametro_configuracion 
                WHERE parametro = 'limite_opciones_respuesta_enlinea' AND seccion = 'RESPUESTAS'; ";
        $res = kolla::db()->consultar_fila($sql);
        $this->cantidad_maxima_opciones_respuesta = $res['valor'];

        $sql = "SELECT valor 
                FROM kolla.sge_parametro_configuracion 
                WHERE parametro = 'limite_tamaño_opciones_respuesta_enlinea' AND seccion = 'RESPUESTAS'; ";
        $res = kolla::db()->consultar_fila($sql);
        $this->longitud_maxima_opcion_respuesta = $res['valor'];

        $sql = "SELECT valor 
                FROM kolla.sge_parametro_configuracion 
                WHERE parametro = 'limite_opciones_respuesta_impresas' AND seccion = 'RESPUESTAS'; ";
        $res = kolla::db()->consultar_fila($sql);
        $this->cantidad_maxima_opciones_respuesta_impresas = $res['valor'];
	}
	
	/*
	 * Incrementa en uno el contador y lo retorna.
	 */
	public function get_contador_diferidas()
	{
		return $this->contador_diferidas++;
	}
	
	/*
	 * Retorna la lista de indicadores que se postergaron por alguna razón.
	 * Del tipo (1) Lista opciones para 1.
	 * 			(2) Lista opciones para 2.
	 * 			...
	 */
	public function get_listado_respuestas()
	{
		$html = '';
		if (!empty($this->lista_respuestas)) {
			foreach ($this->lista_respuestas as $respuesta) {
				$html .= "<br>";
				$html .= '<b>'.$respuesta['indicador']."</b> ".$respuesta['listado'];
			}
            $html .= "<br>";
		}
		return $html;
	}
	
	/*
	 * En caso de que la pregunta todavía no se haya agregado, la agrega al array de indicadores/respuestas.
	 */
	public function escribir_diferido($id_pregunta, $opciones_respuesta)
	{
		$indice = array_search($id_pregunta, kolla_arreglos::aplanar_matriz_sin_nulos($this->lista_respuestas, 'pregunta'));
					
		if ($indice === false) {
			$indicador_respuesta['pregunta']  = $id_pregunta;
			$indicador_respuesta['indicador'] = '('.$this->contador_diferidas.')';
			$indicador_respuesta['listado']   = kolla_texto::armar_lista(kolla_arreglos::aplanar_matriz_sin_nulos($opciones_respuesta, 'respuesta_valor'), ' / ');
			array_push($this->lista_respuestas, $indicador_respuesta);
		} else {
			$this->lista_respuestas[$indice]['indicador'] .= '('.$this->contador_diferidas.')';
		}
	}
	
	/**
	 * En caso de que la cantidad de opciones de la respuesta supere el máximo estipulado, ó 
	 * la longitud promedio de ellas supere la longitud máxima establecida entonces devuelve
	 * true, y false en caso contrario.
	 */
	public function diferir_respuesta($opciones_respuesta)
	{
        $cantidad_opciones_resp = count($opciones_respuesta);
        $promedio_longitud_resp = kolla_arreglos::promedio_longitud_campo_matriz($opciones_respuesta, 'respuesta_valor');
        return (($cantidad_opciones_resp > $this->cantidad_maxima_opciones_respuesta && $cantidad_opciones_resp < $this->cantidad_maxima_opciones_respuesta_impresas) ||
                ($cantidad_opciones_resp > ($this->cantidad_maxima_opciones_respuesta/2) && $promedio_longitud_resp > ($this->longitud_maxima_opcion_respuesta*0.75)) ||
                 $promedio_longitud_resp >= $this->longitud_maxima_opcion_respuesta);
	}
        
    public function mostrar_mensaje_eliminadas($mensaje) 
    {
        if (!$this->hay_eliminadas) {
            $indicador_respuesta['pregunta']  = 0;
            $indicador_respuesta['indicador'] = '(0)';
            $indicador_respuesta['listado']   = $mensaje;
            array_unshift($this->lista_respuestas, $indicador_respuesta);
            $this->hay_eliminadas = true;
        }
    }
	
}
?>