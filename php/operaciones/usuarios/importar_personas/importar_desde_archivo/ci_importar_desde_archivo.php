<?php

use ext_bootstrap\componentes\bootstrap_ci;

class ci_importar_desde_archivo extends bootstrap_ci
{
    protected $s__form_archivo;
    protected $s__proceso;
    protected $s__form_usuarios;
    protected $s__path;
    protected $s__form_totales;
    
    function conf()
    {
        if ($this->get_id_pantalla() == 'pant_grupo') {
            $this->pantalla()->evento('cambiar_tab__siguiente')->set_etiqueta('&Importar');
            $this->pantalla()->evento('cambiar_tab__siguiente')->set_imagen('glyphicon-import', 'proyecto');
            $this->pantalla()->evento('cambiar_tab__siguiente')->set_msg_confirmacion('Tenga en cuenta que el proceso eliminará datos incorrectos de importaciones previas. ¿Desea continuar?.');
        } elseif ($this->get_id_pantalla() == 'pant_resultados') {
            $this->pantalla()->evento('cambiar_tab__anterior')->anular();
        }
    }
            
    function evt__pant_resultados__entrada()
    {
        $importador = new importador_usuarios_archivo('archivo');
        $importador->set_archivo($this->s__path);
        $importador->set_separador($this->s__form_archivo['separador']);
        $importador->set_actualiza_datos_personales($this->s__form_usuarios['actualizar_persona']);
        
        if ($this->s__form_usuarios['grupo_existente']) {
            $importador->set_grupo($this->s__form_usuarios['grupo']);
        } else {
            $importador->set_grupo_nombre($this->s__form_usuarios['grupo_nombre']);
            $importador->set_grupo_descripcion($this->s__form_usuarios['grupo_descripcion']);
            $importador->set_grupo_unidad_gestion($this->s__form_usuarios['grupo_unidad_gestion']);
        }
        
        $importador->importar();
        
        $this->s__form_totales = array(
            'cant_nuevos'           => $importador->get_cant_nuevos(),
            'cant_actualizados'     => $importador->get_cant_actualizados(),
            'cant_agregados_grupo'  => $importador->get_cant_agregados_grupo(),
            'cant_error_datos'      => $importador->get_cant_error_datos(),
            'cant_error_registro'   => $importador->get_cant_error_registro(),
            'cant_error_arai'       => $importador->get_cant_error_arai()
        );
        
        toba::notificacion()->info($this->get_mensaje('importacion_ok'));
    }
	
    function conf__form_archivo(toba_ei_formulario $form)
    {
        if (isset($this->s__form_archivo)) {
            $form->set_datos($this->s__form_archivo);
        }
    }

    function evt__form_archivo__modificacion($datos)
    {
        $this->s__form_archivo = $datos;
        $this->s__path = toba::proyecto()->get_path_temp().'/'.$datos['archivo']['name'];
        
        // Mover los archivos subidos al servidor del directorio temporal PHP a uno propio.
        move_uploaded_file($datos['archivo']['tmp_name'], $this->s__path);
    }
    
    function conf__form_usuarios(toba_ei_formulario $form)
    {
        if (isset($this->s__form_usuarios)) {
            $form->set_datos($this->s__form_usuarios);
        }
    }
    
    function evt__form_usuarios__modificacion($datos)
    {
        $this->validar_datos($datos);
        $this->s__form_usuarios = $datos;
    }
    
    function validar_datos($datos)
	{
        if (!isset($datos['grupo_unidad_gestion'])) {
            throw new toba_error('<b>Unidad de Gestión</b> es obligatorio.');
        }
            
        if ($datos['grupo_existente'] == 1) {
            if (!isset($datos['grupo'])) {
                throw new toba_error('<b>Grupo</b> es obligatorio.');
            }
        } else {
            if (!isset($datos['grupo_nombre'])) {
                throw new toba_error('<b>Nombre del grupo</b> es obligatorio.');
            }
        }
	}
    
    function conf__form_totales(form_totales $form)
    {
        $form->set_datos($this->s__form_totales);
        $form->set_solo_lectura();

        // Si la instalación no esta vinculada con ARAI, no se muestra el conteo de estos errores.
        if (!toba::instalacion()->vincula_arai_usuarios()) {
            $form->desactivar_efs(array('cant_error_arai'));
        }
    }

}