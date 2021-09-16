<?php

use SIUToba\rest\rest;
use SIUToba\rest\lib\rest_error;
use SIUToba\rest\lib\rest_filtro_sql;
use SIUToba\rest\lib\rest_hidratador;

class rest_encuestas extends rest_base 
{
    protected $modelo;
    
    function __construct()
    {
        $this->modelo = kolla::co('co_encuestas');
    }
    
    protected function get_modelo($nombre)
	{
		$modelos = recurso_encuestas::_get_modelos();
		return $modelos[$nombre];
	}    
    
    //MOVIDO DE recurso_encuestas tal como estaba para no introducir cambios a lo existente
    public function get_list()
    {
        $filtro = new rest_filtro_sql();
		$this->filtrar_ug($filtro);
		$this->_get_sistema();
		$filtro->agregar_campo_flag('activas', 'estado = "A"', 'estado = B');

		$sql = "SELECT
					encuesta,
					nombre,
					estado,
					descripcion,
					texto_preliminar
				FROM sge_encuesta_atributo
				WHERE
				{$filtro->get_sql_where()}
				{$filtro->get_sql_limit()}
				{$filtro->get_sql_order_by('+encuesta')}
				";
		$datos = kolla_db::consultar($sql);
        return $datos;
    }
    
    //MOVIDO DE recurso_encuestas tal como estaba para no introducir cambios a lo existente
    public function get($encuesta) 
    {
        $filtro = new rest_filtro_sql();
		$this->filtrar_ug($filtro);
		$this->_get_sistema();
		$filtro->agregar_campo_simple_local('encuesta', 'encuesta = %s', $encuesta);

		$sql = "SELECT
					encuesta,
					nombre,
					estado,
					descripcion,
					texto_preliminar
				FROM sge_encuesta_atributo
				WHERE
				{$filtro->get_sql_where()}
				{$filtro->get_sql_order_by('+encuesta')}
				";
		$datos = kolla_db::consultar_fila($sql);

		if (empty($datos)) {
			return rest::response()->not_found();
		}

		$sql = "SELECT
		            b.nombre AS bloque_nombre,
		            p.pregunta AS pregunta,
		            p.nombre AS pregunta_nombre,
		            cp.componente as componente,
		            ed.obligatoria as obligatoria
		            , ed.encuesta_definicion as encuesta_definicion
				FROM
					sge_encuesta_definicion ed
						INNER JOIN sge_pregunta p ON (ed.pregunta = p.pregunta)
						INNER JOIN sge_bloque b ON (ed.bloque = b.bloque)
						INNER JOIN sge_componente_pregunta cp ON (cp.numero = p.componente_numero)
				WHERE
					ed.encuesta = ". (int)($encuesta)."
				ORDER BY b.orden, ed.orden;";
		$preguntas = kolla::db()->consultar($sql);
		$datos['preguntas'] = $preguntas;
		
        return $datos;
    }


    public function get_bloques_list($id_encuesta) 
    {
        $this->validar_encuesta($id_encuesta);
        
        $ug = $this->_get_ug(); 
        $this->_get_sistema();
        $datos = $this->modelo->get_bloques($id_encuesta, $ug);
        
        $bloques = array();
        foreach($datos as $bl) {
            $b = array();
            $b['bloque'] = $bl['bloque'];
            $b['nombre'] = $bl['bloque_nombre'];
            $b['descripcion'] = $bl['bloque_descripcion'];
            $b['orden'] = $bl['bloque_orden'];
            
            $bloques[] = $b;
        }
        
        $encuesta = rest_hidratador::hidratar_fila($this->get_modelo('EncuestaSD'), $datos[0]);
        $encuesta['detalle'] = $bloques;
        return $encuesta;
    }
    
    /**
     * Retorna un listado de preguntas que se encuentran en un dado bloque 
     * para una dad encuesta.
     * 
     * @param string $id_encuesta
     * @param string $id_bloque
     */
    public function get_bloques_preguntas_list($id_encuesta, $id_bloque) 
    {    
        $this->validar_encuesta($id_encuesta);
        $this->validar_bloque($id_encuesta, $id_bloque);
        $ug = $this->_get_ug();
        $this->_get_sistema();
        $datos = $this->modelo->get_bloques_preguntas($id_encuesta, $id_bloque, $ug);

        $preguntas = array();
        foreach($datos as $preg) {
            $p = array();
            $p['pregunta'] = $preg['pregunta'];
            $p['nombre'] = $preg['pregunta_nombre'];
            $p['componente'] = $preg['componente'];
            $p['descripcion_resumida'] = $preg['descripcion_resumida'];
            $p['es_libre'] = $preg['es_libre'];
            $p['es_multiple'] = $preg['es_multiple'];
            $p['obligatoria'] = $preg['obligatoria'];            
            $p['pregunta_orden_bloque'] = $preg['pregunta_orden'];
            
            $preguntas[] = $p;
        }
        
        $bloque = rest_hidratador::hidratar_fila($this->get_modelo('BloqueSD'), $datos[0]);
        $bloque['detalle'] = $preguntas;
        $encuesta = rest_hidratador::hidratar_fila($this->get_modelo('EncuestaSD'), $datos[0]);
        $encuesta['detalle'] = $bloque;
        
        return $encuesta;
    }
    
    public function get_bloques_preguntas_respuestas_list($id_encuesta, $id_bloque, $id_pregunta) 
    {
        $this->validar_encuesta($id_encuesta); //Valido la existencia de la encuesta        
        $this->validar_bloque($id_encuesta, $id_bloque); //Valido la existencia del bloque dentro de la encuesta
        $this->validar_pregunta($id_encuesta, $id_bloque, $id_pregunta);
        $ug = $this->_get_ug();
        $this->_get_sistema();
        $datos = $this->modelo->get_bloques_preguntas_respuestas($id_encuesta, $id_bloque, $id_pregunta, $ug);
        
        $respuestas = array();
        foreach ($datos as $resp) {
            $r = array();
            $r['respuesta'] = $resp['respuesta'];
            $r['respuesta_valor'] = $resp['respuesta_valor'];
            
            $respuestas[] = $r;
        }
        
        $pregunta = rest_hidratador::hidratar_fila($this->get_modelo('PreguntasBloque'), $datos[0]);
        $pregunta['detalle'] = $respuestas;
        $bloque = rest_hidratador::hidratar_fila($this->get_modelo('BloqueSD'), $datos[0]);
        $bloque['detalle'] = $pregunta;
        $encuesta = rest_hidratador::hidratar_fila($this->get_modelo('EncuestaSD'), $datos[0]);
        $encuesta['detalle'] = $bloque;
                
        return $encuesta;
    }    
    
	protected function validar_encuesta($encuesta)
	{
		if ( !is_numeric($encuesta) ) {
			throw new rest_error(400, "La encuesta $encuesta no es valida");
		}
        
        $ug = kolla_db::quote($this->_get_ug());
        
		$sql = "SELECT      estado,
                            implementada,
                            nombre,
                            unidad_gestion
				FROM        sge_encuesta_atributo
				WHERE       encuesta = $encuesta
                        AND unidad_gestion = $ug";

		$res = kolla_db::consultar_fila($sql);

		if ( empty($res) ) {
			throw new rest_error(400, "La encuesta $encuesta no existe");
		}
		if ( $res['estado'] != 'A' ) {
			throw new rest_error(400, "La encuesta $encuesta no está activa");
		}	
		
		return $encuesta;
	}
	
	/**
	 * Función que permite la validación sobre la existencia de un determinado bloque 
	 * dentro de una encuesta. En caso de no existir se lanza un error
	 * 
	 * @param unknown $idencuesta
	 * @param unknown $idbloque
	 */
	protected function validar_bloque($idencuesta,$idbloque)
	{
		if(!is_numeric($idbloque))
		{
			throw new rest_error(400, "El bloque $idbloque no es valido");
		}
		
		$ug = kolla_db::quote($this->_get_ug());
		$sql = "SELECT * 
				FROM sge_encuesta_definicion INNER JOIN sge_encuesta_atributo ON sge_encuesta_definicion.encuesta = sge_encuesta_atributo.encuesta
				WHERE 	sge_encuesta_definicion.encuesta = $idencuesta AND
						unidad_gestion = $ug AND
						bloque = $idbloque
				";
		
		$res = kolla_db::consultar_fila($sql);
		if ( empty($res) ) {
			throw new rest_error(400, "El bloque $idbloque no existe dentro de la encuesta $idencuesta");
		}
	}
	
	/**
	 * Realiza la validacion sobre la existencia de la pregunta dentro del bloque en
	 * una dada encuesta.
	 * 
	 * @param int $idencuesta
	 * @param int $idbloque
	 * @param int $idpregunta
	 * @throws rest_error valor incorrecto en la variable de la preunta o la pregunta no existe
	 */
	protected function validar_pregunta($idencuesta,$idbloque,$idpregunta)
	{
		
		if(!is_numeric($idpregunta))
		{
			throw new rest_error(400, "La pregunta $idpregunta no es valida");
		}
		
		$ug = kolla_db::quote($this->_get_ug());
		$sql = "SELECT *
				FROM sge_encuesta_definicion INNER JOIN sge_encuesta_atributo ON sge_encuesta_definicion.encuesta = sge_encuesta_atributo.encuesta
				WHERE 	sge_encuesta_definicion.encuesta = $idencuesta AND
						unidad_gestion = $ug AND
						bloque = $idbloque AND
						pregunta = $idpregunta
				";
		
		$res = kolla_db::consultar_fila($sql);
		if ( empty($res) ) {
			throw new rest_error(400, "La pregunta $idpregunta no existe dentro del bloque $idbloque");
		}
	}
}