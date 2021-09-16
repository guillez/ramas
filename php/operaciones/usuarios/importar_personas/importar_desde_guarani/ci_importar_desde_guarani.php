<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_importar_desde_guarani extends bootstrap_ci
{
    protected $s__datos_conexion;
    protected $s__datos_usuarios;
    protected $s__datos_totales;
    
    function conf()
    {
        if ( $this->get_id_pantalla() == 'pant_usuarios' ) {
            $this->pantalla()->evento('cambiar_tab__siguiente')->set_etiqueta('&Importar');
            $this->pantalla()->evento('cambiar_tab__siguiente')->set_imagen('glyphicon-import', 'proyecto');
            $this->pantalla()->evento('cambiar_tab__siguiente')->set_msg_confirmacion('Antes de iniciar la importación de usuarios recuerde que también se actualizarán los datos de la institución.');
        }
    }
    
    function conf__form_conexion(toba_ei_formulario $form)
    {
        if ( isset($this->s__datos_conexion) ) {
            $form->set_datos($this->s__datos_conexion);
        }
    }
    
    function evt__form_conexion__modificacion($datos)
    {
        $this->s__datos_conexion = $datos;
    }
    
    function conf__form_usuarios(toba_ei_formulario $form)
    {
        if ( isset($this->s__datos_usuarios) ) {
            $form->set_datos($this->s__datos_usuarios);
        }
    }
    
    function evt__form_usuarios__modificacion($datos)
    {
        $this->s__datos_usuarios = $datos;
    }
    
    function conf__form_totales(toba_ei_formulario $form)
	{
        $form->set_datos($this->s__datos_totales);
		$form->set_solo_lectura();

        // Si la instalación no esta vinculada con ARAI, no se muestra el conteo de estos errores.
        if (!toba::instalacion()->vincula_arai_usuarios()) {
            $form->desactivar_efs(array('cant_error_arai'));
        }
	}
    
    function evt__pant_resultados__entrada()
    {
        $importador = new importador_usuarios_ws($this->s__datos_conexion['conexion']);
        $importador->set_actualiza_datos_personales(in_array('actualizar_datos_persona', $this->s__datos_usuarios['persona_existe']));
        $importador->set_agregar_datos_titulos(in_array('actualizar_datos_titulos', $this->s__datos_usuarios['persona_existe']));
        
        if ($this->s__datos_usuarios['grupo_existente']) {
            $importador->set_grupo($this->s__datos_usuarios['grupo']);
        } else {
        	$importador->set_grupo_nombre($this->s__datos_usuarios['grupo_nombre']);
            $importador->set_grupo_descripcion($this->s__datos_usuarios['grupo_descripcion']);
            $importador->set_grupo_unidad_gestion($this->s__datos_conexion['unidad_gestion']);
        }
        
        $importador->importar();
        $this->s__datos_totales = array(
            'cant_nuevos'           => $importador->get_nuevos(),
            'cant_actualizados'     => $importador->get_actualizados(),
            'cant_agregados_grupo'  => $importador->get_agregados_grupo(),
            'cant_error_datos'      => $importador->get_rechazados(),
            'cant_error_registro'   => $importador->get_incorrectos(),
            'cant_error_arai'       => $importador->get_cant_error_arai()
        	);
        
        toba::notificacion()->info($this->get_mensaje('importacion_ok'));
    }
    
    function get_grupos_encuestados_por_ug()
    {
    	$unidad_gestion = kolla_db::quote($this->s__datos_conexion['unidad_gestion']);
    	$where = "sge_grupo_definicion.unidad_gestion = $unidad_gestion";
    	return toba::consulta_php('consultas_usuarios')->get_grupos_encuestados($where);
    }
    
}