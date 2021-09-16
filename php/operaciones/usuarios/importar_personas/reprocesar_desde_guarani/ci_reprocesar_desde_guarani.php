<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_reprocesar_desde_guarani extends bootstrap_ci
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
        $datos = kolla::co('consultas_usuarios')->get_datos_int_guarani_persona("resultado_proceso = 'E'");

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
            kolla::co('consultas_usuarios')->eliminar_datos_int_guarani_persona();
            toba::notificacion()->agregar('Los usuarios se eliminaron correctamente.', 'info');
        } catch (toba_error $e) {
            toba::notificacion()->error('Ocurrió un error eliminando un usuario.');
            toba::logger()->error($e->get_mensaje());
        }
    }

    function evt__eliminar_seleccionados()
    {
        if ( !empty($this->s__marcados) ) {
            try {
                kolla::co('consultas_usuarios')->eliminar_datos_int_guarani_persona($this->s__marcados);
                toba::notificacion()->agregar('Los usuarios seleccionados se eliminaron correctamente.', 'info');
            } catch(toba_error $e) {
                toba::notificacion()->error('Ocurrió un error eliminando un usuario.');
                toba::logger()->error($e->get_mensaje());
            }
        }
    }
    
    function evt__reprocesar()
    {
        try {
            $where = array(
                'fecha_proceso' => $this->s__seleccion['fecha_proceso'],
                'usuario'       => $this->s__seleccion['usuario'],
                'titulo_codigo' => $this->s__seleccion['titulo_codigo'],
            );
            $sql = sql_array_a_update('int_guarani_persona', $this->s__form_persona, $where);
            kolla_db::ejecutar($sql);
            $this->s__marcados = array(
                $this->s__seleccion
            );
            $this->_importar();
            $this->set_pantalla('pant_resultados');
        } catch(toba_error $e) {
            toba::notificacion()->error('Ocurrió un error al intentar importar.');
            toba::logger()->error($e->get_mensaje());
        }
    }

    function evt__reprocesar_todos()
    {
        $this->_importar();
    }

    function evt__reprocesar_seleccionados()
    {
        if ( !empty($this->s__marcados) ) {
            $this->_importar();
        }
    }
    
    private function _importar()
    {
        $importador = new importador_usuarios_ws();
        $importador->set_actualiza_datos_personales(true);
        $importador->set_agregar_datos_titulos(true);
        if ( isset($this->s__marcados) ) {
            $importador->set_seleccion($this->s__marcados);
        }
        $importador->importar();
        $this->s__form_totales = array(
            'cant_nuevos'           => $importador->get_nuevos(),
            'cant_actualizados'     => $importador->get_actualizados(),
            'cant_error_datos'      => $importador->get_rechazados(),
        );
        toba::notificacion()->info('La importación finalizó correctamente');
        $this->set_pantalla('pant_resultados');
    }

    //-----------------------------------------------------------------------------------
	//---- PANTALLA EDITAR PERSONA ------------------------------------------------------
	//-----------------------------------------------------------------------------------
	
	//---- form_persona -----------------------------------------------------------------
	
    function conf__form_persona(toba_ei_formulario $form)
    {
        $form->set_solo_lectura(array('resultado_descripcion'));

        if ( !empty($this->s__seleccion) ) {
            $datos = kolla::co('consultas_usuarios')->get_dato_int_guarani_persona($this->s__seleccion['fecha_proceso'], $this->s__seleccion['usuario'], $this->s__seleccion['titulo_codigo']);
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