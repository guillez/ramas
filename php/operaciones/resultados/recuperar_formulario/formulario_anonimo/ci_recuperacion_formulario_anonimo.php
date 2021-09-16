<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_recuperacion extends bootstrap_ci
{
    protected $s__validado = false;
    protected $s__filtro;
    protected $s__seleccion;
    protected $s__datos_form;

    //-----------------------------------------------------------------------------------
    //---- cuadro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

//	private function get_relacion() 
//	{
//		return $this->dependencia('datos');
//	}

    function conf__cuadro(toba_ei_cuadro $cuadro)
    {
        $where = $this->dep('filtro')->get_sql_where();
        if (isset($this->s__filtro['codigo_recuperacion'])) {
            $datos = toba::consulta_php('consultas_formularios')->get_formulario_con_codigo_recuperacion($where);
            $cuadro->set_datos($datos);
        }
    }


    function evt__cuadro__seleccion($id)
    {
        $this->s__seleccion = $id;
        $this->set_pantalla('seleccion');
    }

    //-----------------------------------------------------------------------------------
    //---- filtro -----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__filtro(toba_ei_filtro $filtro)
    {
        if (isset($this->s__filtro)) {
            $filtro->set_datos($this->s__filtro);
        }
    }

    function evt__filtro__filtrar($datos)
    {
        $this->s__filtro = $datos;
    }

    function evt__filtro__cancelar()
    {
        unset($this->s__filtro);    
    }
    //-----------------------------------------------------------------------------------
    //---- formulario -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function conf__formulario(toba_ei_formulario $form)
    {
        if (!isset($this->s__datos_form))
        {            
            $datos = toba::consulta_php('consultas_formularios')->get_datos_formulario_anonimo_respondido($this->s__seleccion['respondido_formulario']);
            if (!empty($datos)) $this->s__datos_form = $datos[0];
        }
        $moderadas = toba::consulta_php('consultas_formularios')->get_es_moderada($this->s__datos_form['respondido_formulario'], 
                                                                           $this->s__datos_form['codigo_recuperacion']);
        if ($this->s__datos_form['anonima'] == 'S')
        {
            $form->desactivar_efs(array('fecha'));
        }

        if (isset($moderadas))
        {
            $this->pantalla()->agregar_notificacion("La encuesta ha sufrido moderación en al menos una de sus respuestas.", "info");
            $this->dep('formulario')->eliminar_evento('visualizar');
            $this->dep('formulario')->eliminar_evento('calcular_hash');
            $form->set_solo_lectura(array('hash'));
        }
        //else { echo "A"; }


        $form->set_datos($this->s__datos_form);

//		
//		if ($this->s__validado)
//		{
//			$form->eliminar_evento('calcular_hash');
//			$form->agregar_evento('visualizar');
//			$form->set_solo_lectura(array('hash'));
//		}
//		else
//		{
//			$this->dep('formulario')->eliminar_evento('visualizar');
//			$this->dep('formulario')->agregar_evento('calcular_hash');
//		}
    }

    function evt__formulario__calcular_hash($datos)
    {	
//		$hash_usuario = $datos['hash'];
//		$this->s__datos_form['hash'] = $hash_usuario; 
//		$id_enc = $this->s__seleccion['formulario_encabezado'];
//		$hashing = anonimato_utils::hashing_de_id_formulario($id_enc);
//		if ($hash_usuario == $hashing)
//		{
//			$this->pantalla()->agregar_notificacion("Hash validado, presione el botón para ver el contenido de la encuesta", "info");
//			$this->s__validado = true;
//			toba::memoria()->set_dato('formulario_habilitado', $this->s__seleccion['formulario_habilitado']);
//    		toba::memoria()->set_dato('encabezado_formulario', $id_enc);
//			// get_parametro('formulario_habilitado');
//			toba::memoria()->get_parametro('formulario_encabezado');
//		}
//		else 
//		{
//			$mensaje = "El hash no corresponde al formulario de encuesta indicado por el código.";	
//			toba::notificacion()->agregar($mensaje, "error");
//			$this->pantalla()->agregar_notificacion($mensaje, "error");
//			$this->s__validado = false;
//		}
    }


    function evt__formulario__cancelar()
    {
//		unset($this->s__validado);
//		unset($this->s__datos_form);
//		$this->set_pantalla('busqueda');
    }

}
?>