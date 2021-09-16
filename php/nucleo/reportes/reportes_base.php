<?php

/**
 * Description of reportes_base
 *
 * @author Administrador
 */
class reportes_base
{
	
	private $headers = array();
	private $data = array();

	public function get_data(){
		return $this->data;
	}
    
	public function get_headers(){
		return $this->headers;
	}
       
	public function agregar_columna($id, $titulo){
		$this->headers[$id] = $titulo;
	}

    public function quitar_columna($id){
	    unset($this->headers[$id]);
    }
    
	public function agregar_columnas_array(&$id_titulo){
		foreach ($id_titulo as $id=> $titulo){
			$this->headers[$id] = $titulo;
		}
	}
	
	public function set_data(&$matriz){
		$this->data = $matriz;
	}
	
	/**
	*Agrega las columnas a un cuadro toba
	* @param toba_ei_cuadro $cuadro
	* @param array $columnas id->valor
	*/
	public function agregar_columnas_cuadro(toba_ei_cuadro $cuadro){
		foreach($this->headers as $id=>$col){
			$nueva_columna = array("clave" => $id, 
						           "titulo"=>$col);
			$cuadro->agregar_columnas(array($nueva_columna));
		}
	}
		
	/**
	 *Determina si una pregunta es multiple (en base al atributo componente)
	 * @param type $pregunta existe $pregunta['componente']
	 * @return type si es multiple o no
	 */
	protected function is_multiple($componente){
		return 	$componente == 'list' ||
				$componente == 'check';
	}
	
	//queda para todos los reportes a texto
	protected function obtener_reporte_texto()
    {
		$texto = '';
		$linea_texto ='';
		$a=0; //para que no ponga separador en la ultima
		$separator = '';
		foreach($this->headers as $col){
			$trimmed_resp = str_replace(array("\r", "\r\n", "\n"), ' ', $col);
			$linea_texto .= $separator.$trimmed_resp;
			if($a ==0){	$a++; $separator= '|';	}
		}
		
		$texto .= $linea_texto. "\n";
		$a=0;
		foreach($this->data as $fila){
			$linea_texto ='';
			$a=0;
			$separator = '';
			foreach($this->headers as $id =>$item){
				if(isset($fila[$id])){
					$trimmed_resp = str_replace(array("\r", "\r\n", "\n"), ' ', $fila[$id]);
				}else
					$trimmed_resp = '';
				$linea_texto .= $separator.$trimmed_resp;
				if($a ==0){	$a++; $separator= '|';	}
			}
			$texto .= $linea_texto. "\n";
		}
		return $texto;
	}

    //Agregar información a reporte en construcción
	protected function continuar_reporte_texto() {
        $texto = '';
        $linea_texto ='';

        $a=0;
        foreach($this->data as $fila){
            $linea_texto ='';
            $a=0;
            $separator = '';
            foreach($this->headers as $id =>$item){
                if(isset($fila[$id])){
                    $trimmed_resp = str_replace(array("\r", "\r\n", "\n"), ' ', $fila[$id]);
                }else
                    $trimmed_resp = '';
                $linea_texto .= $separator.$trimmed_resp;
                if($a ==0){	$a++; $separator= '|';	}
            }
            $texto .= $linea_texto. "\n";
        }
        return $texto;
	}    
    
	/**
	 *Se fija en la matriz, para cada fila, si existe una columna 'tabla_asociada'
	 * y no es vacia, se setea en una columna 'respuesta_valor' el valor 
	 */
	public function reemplazar_tablas_asociadas() {
		foreach ( $this->data as $clave => $fila ) {
			if ( $fila['tabla_asociada'] != '' ) {
				$this->data[$clave]['respuesta_valor'] = $this->get_valor_tabla_asociada($fila);
			}
		}
	}
	
	/**
	 * Obtiene el valor de una tabla asociada y lo cachea.
	 * @param type $pregunta 
	 */
	public function get_valor_tabla_asociada($fila) {
		$tabla       = $fila['tabla_asociada'];
		$codigo		 = $fila['tabla_asociada_codigo'];
		$descripcion = $fila['tabla_asociada_descripcion'];
		$respuesta   = $fila['respuesta'];
		
		$valor = kolla_db::consultar_fila("SELECT $descripcion FROM $tabla WHERE $codigo = {$respuesta}");
		if ( !empty($valor) ) {
			$this->tablas_asociadas[$tabla][$respuesta] = $valor[$descripcion];
			$nombre = $valor[$descripcion];
		} else {
			$nombre = 'ERROR'; //-- Por alguna razon no se pudo recuperar el valor de la respuesta
		}
		
		return $nombre;		
	}
	
}
