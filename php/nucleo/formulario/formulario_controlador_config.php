<?php

/**
 * Se separa parte de la configuracion del controlador para no llenarlo de ifs
 * y casos particulares. Si hay un nuevo acceso, se crea una nueva configuracion
 * y no hay que modificar el controlador.
 * 
 * La mayoría de las cosas "tendría" que funcionar si no se setea. En especial
 * las cosas nuevas que se agreguen.
 *
 */
class formulario_controlador_config
{
	protected $planilla; //definicion	
	protected $respondido_formulario; //respuestas
	protected $formulario_habilitado; //id definicion
	protected $vista;
	protected $vista_builder;
	protected $formulario; //formulario()
	
	//si tiene url_post, entonces procesa post
	protected $url_post; 
	protected $url_base_fotos;
	
	//si es editable, se pueden introducir/modificar respuestas
	protected $editable = false;
	
	protected $generar_codigo_recuperacion = false;
	
	protected $encuestado;
    protected $respondido_por;
    protected $sistema;
	protected $codigo_externo;
	protected $anonima;
	protected $paginada;
    protected $estilo = null;
    protected $es_guest;
    protected $mostrar_progreso = false;
    
    protected $guardado = false;
		
	public function __construct($id_form_habilitado, $id_respondido_form = null)
	{
		$this->respondido_formulario = $id_respondido_form;
		$this->formulario_habilitado = $id_form_habilitado;
		$this->obtener_planilla($id_form_habilitado);
	}
	
	/**
	 * procesa datos del post? o solo muestra cosas
	 */
	public function procesa_post()
    {
		return isset($this->url_post) && $this->url_post != '';
	}
		
	public function get_vista() 
    {
		if ( !isset($this->vista) ) {
			$this->configurar_vista($this->vista_builder);
		}
		return $this->vista;
	}
	
	public function get_formulario()
    {
		if ( !isset($this->formulario) ) {
			$this->formulario = new formulario($this->planilla, $this->respondido_formulario);
		}
		return $this->formulario;
	}

	public function set_vista_builder(vista_builder $v)
    {
		$this->vista_builder = $v;
	}
	
	public function set_datos_habilitacion($datos_hab)
    {
		$this->set_anonima($datos_hab['anonima'] == 'S');
        $this->set_estilo($datos_hab['estilo']);
        $this->set_paginada($datos_hab['paginado'] == 'S');
		$this->set_url_base_fotos($datos_hab['url_imagenes_base']);
		$this->set_generar_codigo_recuperacion($datos_hab['generar_cod_recuperacion'] == 'S');
		$this->set_imprimir_respuestas_completas($datos_hab['imprimir_respuestas_completas'] == 1);
        $this->set_mostrar_progreso($datos_hab['mostrar_progreso'] == 'S');
		
        if ( isset($datos_hab['encuestado']) ) {
            $this->set_encuestado($datos_hab['encuestado']);
        }
        
		$this->set_sistema($datos_hab['sistema']);
		$this->texto_preliminar = $datos_hab['texto_preliminar'];
	}
	
	public function set_editable($boolean)
    {
		$this->editable = $boolean;
	}
	
	public function set_paginada($boolean)
    {
		$this->paginada =	$boolean;
	}
    
    public function set_estilo($estilo)
    {
		$this->estilo =	$estilo;
	}
    
	public function set_anonima($boolean)
    {
		$this->anonima = $boolean;
	}

	public function set_mostrar_progreso($boolean)
    {
        $this->mostrar_progreso = $boolean;
    }
    
	public function set_sistema($int)
    {
		$this->sistema = $int;
	}
	
	public function set_url_post($url)
    {
		$this->url_post = $url;
	}
	
	public function set_url_base_fotos($url)
    {
		$this->url_base_fotos = $url;
	}
	
	public function set_encuestado($encuestado)
    {
		$this->encuestado = $encuestado;
	}
    
    public function set_respondido_por($respondido_por)
    {
		$this->respondido_por = $respondido_por;
	}
	
	public function set_codigo_externo($codigo_externo)
    {
		$this->codigo_externo = $codigo_externo;
	}
	
	public function set_generar_codigo_recuperacion($boolean)
    {
		$this->generar_codigo_recuperacion = $boolean;
	}
	
	public function set_imprimir_respuestas_completas($imprimir_respuestas_completas)
	{
		$this->imprimir_respuestas_completas = $imprimir_respuestas_completas;
	}
        
        public function set_aviso_guardado($mostrar) 
        {
            $this->guardado = $mostrar;
        }
        
        public function get_aviso_guardado() 
        {
            return $this->guardado;
        }
	
	public function get_imprimir_respuestas_completas()
    {
    	if (!isset($this->imprimir_respuestas_completas)) {
    		if (isset($this->formulario_habilitado)) {
	    		$habilitacion = toba::consulta_php('consultas_habilitaciones')->get_datos_habilitacion_x_form_hab($this->formulario_habilitado);
	    		$this->imprimir_respuestas_completas = $habilitacion['imprimir_respuestas_completas'];
    		} else {
    			$this->imprimir_respuestas_completas = true;
    		}
    	}
    	
    	return $this->imprimir_respuestas_completas;
	}
	
	public function get_generar_codigo_recuperacion()
    {
		return $this->generar_codigo_recuperacion;
	}
	
	public function get_formulario_habilitado()
    {
		return $this->formulario_habilitado;
	}
	
	public function get_respondido_formulario()
    {
		return $this->respondido_formulario;
	}
    
    public function get_plantilla_css()
    {
        $estilo = toba::consulta_php('consultas_encuestas')->get_estilos(array('estilo' => $this->estilo), true);
        return $estilo['archivo'];
    }

	/**
	 * El controlador lo actualiza cuando crea un respondido_formulario nuevo.
	 */
	public function set_respondido_formulario($respondido_formulario)
    {
		$this->respondido_formulario = $respondido_formulario;
	}
	
	public function get_encuestado() 
    {
		return $this->encuestado;
	}
    
    public function get_respondido_por()
    {
        return $this->respondido_por;
    }

    public function get_sistema() 
    {
		return $this->sistema;
	}

	public function get_codigo_externo() 
    {
		return $this->codigo_externo;
	}

	public function get_anonima() 
    {
		return $this->anonima;
	}
	
	public function get_paginada() 
    {
		return $this->paginada;
	}
	
	public function get_url_post()
    {
		return $this->url_post;
	}
	
	public function set_guest($es_guest)
	{
		$this->es_guest = $es_guest;
	}
	
	public function get_puede_guardar()
    {
		return !isset($this->respondido_por) && !$this->anonima &&  $this->url_post && !$this->es_guest;
	}

	public function get_mostrar_progreso()
    {
        return $this->mostrar_progreso;
    }

	protected function configurar_vista(vista_builder $builder)
    {
		$vista =  new formulario_vista($builder);
		$vista->set_puede_guardar($this->get_puede_guardar());
		$vista->set_texto_preliminar($this->get_texto_preliminar());
        
		if ( $this->url_base_fotos ) {
			$vista->set_url_base_fotos($this->url_base_fotos);
		}
		
		$builder->set_editable($this->editable);

		if (isset($this->estilo) ) {
			$builder->set_plantilla_css($this->get_plantilla_css());
		}
        
		if ( $this->paginada ) {
			$builder->set_paginacion(true, $this->get_formulario()->helper_get_lista_bloques());
		} else {
			$builder->set_paginacion(false, null);
		}
			
		if ( $this->procesa_post() ) {
			$vista->set_url_post($this->url_post);
		}

		if ( $this->mostrar_progreso) {
		    $vista->set_mostrar_progreso($this->mostrar_progreso);
        }
        
		$this->vista = $vista;
	}

	protected function obtener_planilla($id_form_habilitado)
	{
        $res = catalogo::consultar(dao_encuestas::instancia(), 'get_planilla_id', array($id_form_habilitado, false));
        $this->planilla = $res;
    }
		
	protected function tiene_respuestas()
    {
		return isset($this->respondido_formulario);
	}
	
	protected function get_texto_preliminar()
    {
		return isset($this->texto_preliminar) ? $this->texto_preliminar : null;
	}
}

?>