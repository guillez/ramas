<?php


/**
 * Permite guardar respuestas aun cuando no se conocen datos como el encabezado
 * del formulario, permitiendo retrazar la especificación de su valor. Preparar 
 * las respuestas requiere ciertor procesamiento, por lo es conveniente
 * pre-generarlas antes de abrir la transaccion (para obtener el id_formulario).
 * 
 * Asimismo se pueden batchear todas las respuestas para una mejor performance. 
 *
 * @author alejandro
 */
class batcher_respuestas {
	
    protected $data;
    protected $sentencia_lib;
    protected $sentencia_tab;
    protected $sentencia_mul;

    protected $terminar;
    protected $anonima;
    protected $encuestado;
    protected $respondido_por;
    protected $sistema;
    protected $codigo_externo;

    protected $respondido_formulario;
    protected $formulario_habilitado;
    protected $codigo_recuperacion;
    protected $version_digest;
	
    function __construct() {}
	
    function guardar_respuesta($sp, $form_hab_det, $id_encuesta_definicion, $valor)
    {
        if ( !isset($this->data[$form_hab_det]) ) {
                $this->data[$form_hab_det] = array();
        }

        switch ($sp) {
            case 'mul':	$sent = $this->sentencia_mul; break;
            case 'lib':	$sent = $this->sentencia_lib; break;
            case 'tab':	$sent = $this->sentencia_tab; break;
			default : throw new Exception("No se encuetra sp para componente");
	    }
        $this->data[$form_hab_det][] = array ($sent , $id_encuesta_definicion, $valor);
    }
		
    function guardar_respondido_formulario($resp_form_id, $formulario_hab, $cod_recuperacion, $ver_digest)
    {
        $this->respondido_formulario = $resp_form_id;
        $this->formulario_habilitado = $formulario_hab;
        $this->version_digest = $ver_digest;
        $this->codigo_recuperacion = $cod_recuperacion;
    }
	
    function set_terminar($terminar)
    {
        $this->terminar = $terminar;
    }
	
    function set_encuestado($encuestado)
    {
        $this->encuestado = $encuestado;
    }
    
    function set_respondido_por($respondido_por) 
    {
        $this->respondido_por = $respondido_por;
    }
            
    function set_sistema($sistema)
    {
        $this->sistema = $sistema;
    }
	
    function set_codigo_externo($codigo_externo)
    {
        $this->codigo_externo = $codigo_externo;
    }
	
    function set_anonima($anonima)
    {
        $this->anonima = $anonima;
    }

    function begin()
    {
        $this->data = array();
        $sql = 'SELECT sp_guarda_respuesta_libre(?,?,?)';
        $this->sentencia_lib = kolla::db()->sentencia_preparar($sql);
        $sql = 'SELECT sp_guarda_respuesta_tabulada(?,?,?)';
        $this->sentencia_tab = kolla::db()->sentencia_preparar($sql);
        $sql = 'SELECT sp_guarda_respuesta_tabulada_array(?,?,?)';
        $this->sentencia_mul = kolla::db()->sentencia_preparar($sql);
    }
	
    /**
     * Guarda todo lo que le dieron desde el begin en la bd.
     */
    function commit()
    {
        kolla::db()->abrir_transaccion();

        if ( is_null($this->respondido_formulario) ) { // Tengo que crear un form nuevo
                $this->respondido_formulario = $this->crear_respondido_formulario($this->codigo_recuperacion, $this->version_digest);
        } else {
                $this->actualizar_respondido_formulario($this->codigo_recuperacion, $this->version_digest);
        }

        $this->guardar_respondido_por($this->respondido_formulario); // Si se responde por el encuestado se setea en BD.
        
        // Insertar formulario y obtener respondido_formulario
        foreach($this->data as $id => $valor) { //para cada encuesta
                $res_enc = $this->upsert_respondido_encuesta($id); //$id = fhd
                foreach ($valor as $respuesta){
                        kolla::logger()->var_dump($respuesta);
                        kolla::db()->sentencia_ejecutar($respuesta[0], array($res_enc, $respuesta[1], $respuesta[2]));
                }
        }

        $this->crear_respondido_encuestado();

        kolla::db()->cerrar_transaccion();
    }
    /*
     *  Factoriazación de la función. La setencia se ejecutaría tanto en 
     * actualizar_respondido_formulario() como en  crear_respondido_formulario().
     * 
     */
	public function guardar_respondido_por($respondido_formulario)
    {
       if ( isset($this->respondido_por) ) { //-- Si se completa por encuestado
           $sql = "INSERT INTO
                        sge_respondido_por (respondido_formulario, encuestado) 
                   VALUES
                        ($respondido_formulario, $this->respondido_por)";

           kolla::db()->ejecutar($sql);
        }
    }
    /**
     * Llamarlo despues del commit, retorna el formulario que se creo durante la ultima transacción.
     */
    public function get_respondido_formulario()
    {
            return $this->respondido_formulario;
    }

    private function actualizar_respondido_formulario($random, $digest)
    {
        if ($random == null) {
            $random = 'NULL';
            $digest = "''";
        } else {
            $random = quote($random);
            $digest = kolla::db()->quote($digest);
        }

        $terminado = $this->terminar ? 'S' : 'N';        
        $fecha_terminado = $this->terminar ? 'current_date' : 'NULL';
                
        $sql = "UPDATE 	sge_respondido_formulario
                SET     fecha_terminado = $fecha_terminado, 
                        codigo_recuperacion = $random,
                        version_digest = $digest,
                        terminado = '$terminado'
                WHERE	respondido_formulario = $this->respondido_formulario";

        if ( kolla::db()->ejecutar($sql) == 0 ) {
                throw new toba_error('No existe el encabezado');
        }
    }
	
    /**
     * Crea el encabezado de formulario - por ahora lo termina siempre!! TODO!!
     * @param type $random
     * @param type $digest
     * @return type el formulario encabezado
     */
    private function crear_respondido_formulario($random, $digest)
    {
        $formulario_hab = kolla::db()->quote($this->formulario_habilitado); //todas las filas iguales, esta de-normalizado

        if ( $random == null ) {
                $random = 'NULL';
                $digest = "''";
        } else {
                $random = quote($random);
                $digest = kolla::db()->quote($digest);
        }

        $terminado = $this->terminar ? 'S' : 'N';
        $fecha_terminado = $this->terminar ? 'current_date' : 'NULL';
        
        $sql = "INSERT INTO sge_respondido_formulario
                (
                        formulario_habilitado, ingreso, fecha, codigo_recuperacion, version_digest, terminado, fecha_terminado
                )
                VALUES
                (
                        $formulario_hab, 1, current_date, $random , $digest, '$terminado', $fecha_terminado
                ) 
                RETURNING respondido_formulario;";
        
        $res = kolla::db()->consultar($sql);
        $respondido_formulario = $res[0]['respondido_formulario'];

        return $respondido_formulario;
    }
	
    private function upsert_respondido_encuesta($fhd)
    {
        $rf  = quote($this->respondido_formulario);
        $fhd = quote($fhd);
        $sql = "
                SELECT 
                    respondido_encuesta
                FROM
                    sge_respondido_encuesta 
                WHERE
                        respondido_formulario = $rf
                                    AND formulario_habilitado_detalle = $fhd
                ";
        
        $res = kolla::db()->consultar($sql);

        if (count($res) == 1) return $res[0]['respondido_encuesta'];

        $sql2 = "
            INSERT INTO 
                sge_respondido_encuesta(respondido_formulario, formulario_habilitado_detalle)
            VALUES 
                ($rf, $fhd)
			RETURNING 
                respondido_encuesta as res_enc
        ";
        
        $res2 = kolla::db()->consultar($sql2);

        return $res2[0]['res_enc'];
    }

	private function crear_respondido_encuestado()
	{
        $sistema    = is_null($this->sistema) ? 'NULL' : quote($this->sistema);
        $cod        = is_null($this->codigo_externo) ? 'NULL' : quote($this->codigo_externo);
        $terminado  = $this->terminar ? 'S': 'N';
        
        if ( $this->anonima ) {
            $terminado = 'NULL';
            $ignorado = 'NULL'; //no se dice si termino o ignoro
            $respondido_form = 'NULL';
            $fecha_terminado = 'current_date';
        } else {
            $terminado = quote($terminado);
            $ignorado = quote('N');
            $respondido_form = quote($this->respondido_formulario);
            $fecha_terminado = 'current_timestamp';
            
            if ( $this->existe_respondido_encuestado($this->formulario_habilitado, $this->respondido_formulario, $this->encuestado) ) {
                $sql = "UPDATE  sge_respondido_encuestado
                        SET     fecha = $fecha_terminado,
                                terminado = $terminado
                        WHERE   formulario_habilitado = $this->formulario_habilitado
                                AND respondido_formulario = $this->respondido_formulario 
                                AND encuestado = $this->encuestado";
                return kolla::db()->ejecutar($sql) == 1;
            }
        }

        $sql = "INSERT INTO     sge_respondido_encuestado 
                                (formulario_habilitado,
                                respondido_formulario,
                                encuestado,
                                sistema,
                                codigo_externo,
                                fecha,
                                terminado, 
                                ignorado)
                VALUES          ($this->formulario_habilitado,
                                $respondido_form,
                                $this->encuestado,
                                $sistema,
                                $cod,
                                $fecha_terminado,
                                $terminado,
                                $ignorado)";

        return kolla::db()->ejecutar($sql) == 1;
	}
	
	protected function existe_respondido_encuestado($fh, $rf, $encu)
    {
		$claves = array(
            'formulario_habilitado' => $fh,
			'respondido_formulario' => $rf,
			'encuestado'            => $encu
        );
		return abm::existen_registros('sge_respondido_encuestado', $claves);
	}
    
}
?>
