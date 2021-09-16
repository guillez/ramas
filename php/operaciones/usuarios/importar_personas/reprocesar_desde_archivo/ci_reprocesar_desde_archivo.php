<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_reprocesar_desde_archivo extends bootstrap_ci
{
    protected $s__seleccion;
    protected $s__marcados;
    protected $s__form_totales;
    protected $s__form_persona;

    //-----------------------------------------------------------------------------------
    //---- PANTALLA DATOS ---------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    //---- cuadro_personas --------------------------------------------------------------
	
    function conf__cuadro_personas(toba_ei_cuadro $cuadro)
    {
        $datos = toba::consulta_php('consultas_usuarios')->get_datos_int_persona("resultado_proceso = 'E'");
        
        if ( !empty($datos) ) {
            $cuadro->set_datos($datos);
        } else {
            $cuadro->set_eof_mensaje($this->get_mensaje('control_reprocesar_persona'));
            $this->pantalla()->eliminar_evento('eliminar_seleccionados');
            $this->pantalla()->eliminar_evento('eliminar_todos');
            $this->pantalla()->eliminar_evento('reprocesar_seleccionados');
            $this->pantalla()->eliminar_evento('reprocesar_todos');
        }
    }

    function evt__cuadro_personas__seleccion($seleccion)
    {
        $this->s__seleccion = $seleccion;
        $this->set_pantalla('pant_editar_persona');
    }

    function evt__cuadro_personas__seleccion_multiple($seleccion)
    {
        $this->s__marcados = $seleccion;
    }
	
    //-----------------------------------------------------------------------------------
    //---- Eventos ----------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function evt__cancelar()
    {
        unset($this->s__form_persona);
        unset($this->s__form_totales);
        unset($this->s__marcados);
        unset($this->s__seleccion);
        $this->set_pantalla('pant_editar_datos');
    }

    function evt__eliminar_todos()
    {
        try {
            toba::consulta_php('consultas_usuarios')->eliminar_datos_int_persona();
            toba::notificacion()->agregar('Los usuarios se eliminaron correctamente.', 'info');
        } catch (toba_error $e) {
            toba::notificacion()->agregar('Ocurrió un error eliminando un usuario.', 'error');
            toba::logger()->error($e->GetMessage());
        }
    }

    function evt__eliminar_seleccionados()
    {
        if (!empty($this->s__marcados)) {
            try {
                toba::consulta_php('consultas_usuarios')->eliminar_datos_int_persona($this->s__marcados);
                toba::notificacion()->agregar('Los usuarios seleccionados se eliminaron correctamente.', 'info');
            } catch(toba_error $e) {
                toba::notificacion()->agregar('Ocurrió un error eliminando un usuario.', 'error');
                toba::logger()->error($e->GetMessage());
            }
        }
    }
    
    function evt__reprocesar()
    {
        try {
            $where = array('persona' => $this->s__seleccion['persona']);
            $sql = sql_array_a_update('int_persona', $this->s__form_persona, $where);
            kolla_db::ejecutar($sql);
            $this->s__marcados = array(
                $this->s__seleccion
            );
            $this->_importar();
            $this->set_pantalla('pant_resultados');
        } catch(toba_error $e) {
            toba::notificacion()->error('Ocurrió un error al intentar importar.');
            throw $e;
        }
    }

    function evt__reprocesar_todos()
    {
        $this->_importar();
        $this->set_pantalla('pant_resultados');
    }

    function evt__reprocesar_seleccionados()
    {
        if ( !empty($this->s__marcados )) {
            $this->_importar();
            $this->set_pantalla('pant_resultados');
        }
    }
    
    private function _importar()
    {
        $importador = new importador_usuarios_archivo('int_persona');
        $importador->set_actualiza_datos_personales(true);
        
        if ( isset($this->s__marcados) ) {
            $seleccion = rs_convertir_asociativo($this->s__marcados, array('persona'), 'persona');
            $importador->set_seleccion($seleccion);
        }
        
        $importador->importar();
        
        $this->s__form_totales = array(
            'cant_nuevos'       => $importador->get_cant_nuevos(),
            'cant_actualizados' => $importador->get_cant_actualizados(),
            'cant_error_datos'  => $importador->get_cant_error_datos(),
        );
        
        toba::notificacion()->info('La importación finalizó correctamente');        
    }

    //-----------------------------------------------------------------------------------
    //---- PANTALLA EDITAR PERSONA ------------------------------------------------------
    //-----------------------------------------------------------------------------------

    //---- form_persona -----------------------------------------------------------------

    function conf__form_persona(toba_ei_formulario $form)
    {
        $form->set_solo_lectura(array('resultado_descripcion'));

        if ( !empty($this->s__seleccion) ) {
            $datos = toba::consulta_php('consultas_usuarios')->get_dato_int_persona($this->s__seleccion['persona']);
            $form->set_datos($datos);
        }
    }

    function evt__form_persona__modificacion($datos)
    {
        $this->s__form_persona = $datos;
    }
	
    //-----------------------------------------------------------------------------------
    //---- PANTALLA RESULTADOS TOTALES --------------------------------------------------
    //-----------------------------------------------------------------------------------

    //---- form_totales -----------------------------------------------------------------

    function conf__form_totales(toba_ei_formulario $form)
    {
        $form->set_datos($this->s__form_totales);
        $form->set_solo_lectura();
    }

    //-----------------------------------------------------------------------------------
    //---- JAVASCRIPT -------------------------------------------------------------------
    //-----------------------------------------------------------------------------------

    function extender_objeto_js()
    {
        echo "

        //---- Eventos ---------------------------------------------

        {$this->objeto_js}.evt__eliminar_seleccionados = function()
        {
            return this.verificar_seleccion();
        }

        {$this->objeto_js}.evt__reprocesar_seleccionados = function()
        {
            return this.verificar_seleccion();
        }

        {$this->objeto_js}.verificar_seleccion = function()
        {
            if (this.dep('cuadro_personas').get_ids_seleccionados('seleccion_multiple').length == 0) {
                alert('Debe seleccionar al menos un registro.');
                return false;
            }

            return true;
        }

        ";
    }	

}
?>