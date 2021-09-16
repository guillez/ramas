<?php

class ci_recuperacion extends ci_navegacion
{
    protected $s__validado = false;
    protected $s__datos_form;

    private function get_relacion() 
    {
        return $this->dependencia('datos');
    }

    //-----------------------------------------------------------------------------------
    //---- Pantalla Búsqueda ------------------------------------------------------------
    //-----------------------------------------------------------------------------------
    
    function conf__filtro(toba_ei_filtro $filtro) 
    {
        $filtro->columna('codigo_recuperacion')->set_condicion_fija('es_igual_a', true);
        parent::conf__filtro($filtro);
    }
	
    //---- cuadro -----------------------------------------------------------------------

    function conf__cuadro(toba_ei_cuadro $cuadro)
    {
        if (isset($this->s__filtro) && ($this->s__filtro != '')) {
            $this->indx_msg_eof .= '_filtrado';
            $datos = toba::consulta_php('consultas_formularios')->get_formularios_respondidos($this->get_filtro_condicion());
            $cuadro->set_datos($datos);
        }

        $cuadro->set_eof_mensaje($this->get_mensaje($this->indx_msg_eof, $this->get_etiquetas_cuadro()));
    }
	
    function get_etiquetas_cuadro()
    {
        return array('encuestas');
    }

    function evt__cuadro__seleccion($id)
    {
        $this->s__seleccion = $id;
        $this->set_pantalla('seleccion');
    }
	
    //-----------------------------------------------------------------------------------
    //---- Pantalla Edición -------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    //---- formulario -------------------------------------------------------------------

    function conf__formulario(toba_ei_formulario $form)
    {
        $where = 'sge_respondido_formulario.respondido_formulario = '.$this->s__seleccion['respondido_formulario'];
        $datos = toba::consulta_php('consultas_formularios')->get_formularios_respondidos($where);
        $this->s__datos_form = $datos[0];
        $form->set_datos($this->s__datos_form);
        $moderadas = array();

        if ($this->s__datos_form['anonima'] == 'S') {
            $form->desactivar_efs(array('fecha_formato_visual'));
        }

        if (!empty($moderadas) && count($moderadas[0]['cantidad_moderadas']) > 0) {
            $this->pantalla()->agregar_notificacion('La encuesta ha sufrido moderación en al menos una de sus respuestas.', 'info');
            $this->dep('formulario')->eliminar_evento('visualizar');
            $this->dep('formulario')->eliminar_evento('calcular_hash');
            $form->set_solo_lectura(array('hash'));
            return;
        }

        if ($this->s__validado) {
            $form->eliminar_evento('calcular_hash');
            $form->agregar_evento('visualizar');
            $form->set_solo_lectura(array('hash'));
        } else {
            $this->dep('formulario')->eliminar_evento('visualizar');
            $this->dep('formulario')->agregar_evento('calcular_hash');
        }
    }

    function evt__formulario__calcular_hash($datos)
    {	
        $hash_usuario = $datos['hash'];
        $this->s__datos_form['hash'] = $hash_usuario; 
        $id_enc = $this->s__seleccion['respondido_formulario'];
        $hashing = anonimato_utils::hashing_de_id_formulario($id_enc);

        if ($hash_usuario == $hashing) {
            $this->pantalla()->agregar_notificacion('Hash validado, presione el botón para ver el contenido de la encuesta', 'info');
            $this->s__validado = true;
            toba::memoria()->set_dato('formulario_habilitado', $this->s__seleccion['formulario_habilitado']);
        toba::memoria()->set_dato('respondido_formulario', $id_enc);
        } else {
            $mensaje = 'El hash no corresponde al formulario de encuesta indicado por el código.';
            toba::notificacion()->agregar($mensaje, 'error');
            $this->pantalla()->agregar_notificacion($mensaje, 'error');
            $this->s__validado = false;
        }
    }

    function evt__formulario__cancelar()
    {
        unset($this->s__validado);
        unset($this->s__datos_form);
        $this->set_pantalla('busqueda');
    }

}
?>