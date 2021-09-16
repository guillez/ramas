<?php

class ci_tablas_asociadas extends ci_navegacion_por_ug
{
    protected $s__tabla;
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA SELECCION ------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    //------------------------------------------
    //---- cuadro ------------------------------
    //------------------------------------------

    function get_listado()
	{
        $this->set_ug();
        $filtro_ug = "unidad_gestion = ".kolla_db::quote($this->s__ug);
        
        return act_tablas_asociadas::get_tablas_asociadas($filtro_ug);
	}
	
	function get_etiquetas_cuadro()
	{
		return array('tablas');
	}
    
    function evt__cuadro__seleccion($seleccion)
    {
        $this->s__seleccion = $seleccion;
        $this->set_pantalla('pant_edicion');
    }
    
    //------------------------------------------------------------------------------------
	//---- PANTALLA EDICION --------------------------------------------------------------
	//------------------------------------------------------------------------------------
    
    function conf__pant_edicion(toba_ei_pantalla $pantalla)
    {
        if ( isset($this->s__seleccion) && act_tablas_asociadas::fue_utilizada_en_definicion($this->s__seleccion['tabla_asociada_nombre']) ) {
            $this->dep('tabla')->set_solo_lectura();
            $this->dep('datos')->set_solo_lectura();
            $this->dep('datos')->desactivar_agregado_filas();
            $pantalla->eliminar_evento('eliminar');
            $pantalla->eliminar_evento('guardar');
            $pantalla->set_modo_descripcion(false);
            $pantalla->set_descripcion('No se permite editar una tabla asociada que ha sido utilizada en una definición de encuesta.', 'info');
        } elseif ( !isset ($this->s__seleccion) ) {
            $pantalla->eliminar_evento('eliminar');
        }
    }
    
    //------------------------------------------
    //---- tabla -------------------------------
    //------------------------------------------
    
    function conf__tabla(toba_ei_formulario $form)
    {
        if ( isset($this->s__tabla) ) {
            $datos = $this->s__tabla;
        } elseif ( isset($this->s__seleccion) ) {
            $tabla = act_tablas_asociadas::get_info_tabla_asociada($this->s__seleccion['tabla_asociada_nombre']);
            $tabla['nombre'] = substr($this->s__seleccion['tabla_asociada_nombre'], 3);
            $datos = $tabla;
        }
        
        if ( $this->s__seleccion ) { // No se permite cambiar el nombre de la tabla
            $form->set_solo_lectura(array('nombre'));
        }
        $datos['unidad_gestion'] = $this->s__ug;
        $form->set_datos($datos);
    }
    
    function evt__tabla__modificacion($datos)
    {
        $this->s__tabla = $datos;
    }
    
    //------------------------------------------
    //---- datos -------------------------------
    //------------------------------------------
    
    function conf__datos(toba_ei_formulario_ml $form)
    {
        $form->set_modo_descripcion(false);
        $form->set_descripcion('Recuerde no ingresar descripciones duplicadas para las respuestas ya que internamente cada una tendrá un código diferente.', 'info');
        
        if ( isset($this->s__datos) ) {
            $form->set_datos($this->s__datos);
        } elseif ( isset($this->s__seleccion) ) {
            $datos = act_tablas_asociadas::get_datos_tabla_asociada($this->s__seleccion['tabla_asociada_nombre']);
            $form->set_datos($datos);
        }
    }
    
    function evt__datos__modificacion($datos)
    {
        $this->s__datos = $datos;
    }
    
    function validar_tabla()
    {
        // Esta validazión se hace porque el campo sge_pregunta.tabla_asociada es un varchar(50).
        // Al nombre de la tabla se lo prefija con "ta_"
        if ( strlen($this->s__tabla['nombre']) > 47 ) {
            throw new toba_error('La longitud del campo nombre no puede superar los 47 caracteres');
        }
    }
    
    //------------------------------------------
    //---- Eventos -----------------------------
    //------------------------------------------
    
    function evt__agregar()
    {
        $this->set_pantalla('pant_edicion');
    }
    
    function evt__eliminar()
    {
        try {
            toba::db()->abrir_transaccion();
            
            act_tablas_asociadas::eliminar($this->s__seleccion['tabla_asociada'], $this->s__seleccion['tabla_asociada_nombre']);
            
            toba::db()->cerrar_transaccion();
            $this->evt__cancelar();
            toba::notificacion()->info('La tabla asociada se eliminó correctamente.');
        } catch (toba_error_db $ex) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->error('Ocurrió un error al intentar eliminar la tabla asociada.');
            throw $ex;
        }
    }
    
    function evt__cancelar()
    {
        $this->resetear();
        $this->set_pantalla('pant_seleccion');
    }
    
    function evt__guardar()
    {
        $this->validar_tabla();
        try {
            toba::db()->abrir_transaccion();
            
            if ( isset($this->s__seleccion) ) { // modificacion
                act_tablas_asociadas::modificacion($this->s__seleccion['tabla_asociada_nombre'], $this->s__tabla['descripcion'], $this->s__datos);
            } else { // alta
                act_tablas_asociadas::alta($this->s__tabla, $this->s__datos, $this->s__ug);
            }
            
            toba::db()->cerrar_transaccion();
            $this->evt__cancelar();
        } catch (toba_error $ex) {
            toba::db()->abortar_transaccion();
            toba::notificacion()->error('Ocurrió un error al intentar la operación sobre la tabla asociada.');
            throw $ex;
        }
    }
    
    //------------------------------------------
    //---- Auxiliares --------------------------
    //------------------------------------------
    
    function resetear()
    {
        unset($this->s__seleccion);
        unset($this->s__tabla);
        unset($this->s__datos);
    }
    
}