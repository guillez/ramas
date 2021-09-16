<?php
    include_once('nucleo/formulario/formulario_controlador_config.php');
    include_once('nucleo/formulario/vista/builder_internos.php');
   	
    $fh               = toba::memoria()->get_parametro('fh');
    $estilo           = toba::memoria()->get_parametro('estilo');
    $paginado         = toba::memoria()->get_parametro('paginado');
    $habilitacion     = toba::memoria()->get_parametro('habilitacion');
    $texto_preliminar = toba::memoria()->get_parametro('texto_preliminar');
	
	if (is_null($fh) || empty($fh) || is_null($habilitacion) || empty($habilitacion)) {
		return;
	}
    
	$datos_hab = toba::consulta_php('consultas_habilitaciones')->get_datos_habilitacion($habilitacion);
    
    if (isset($texto_preliminar) && !empty($texto_preliminar)) {
        $datos_hab[0]['texto_preliminar'] = urldecode($texto_preliminar);
    }
    
    $config  = new formulario_controlador_config($fh);
	$config->set_datos_habilitacion($datos_hab[0]);
    $config->set_paginada($paginado == 'S');
    $config->set_estilo($estilo);
    $config->set_editable(true);
    $builder = new builder_internos();
	$formulario_controlador = new formulario_controlador();
	
	$config->set_vista_builder($builder);
	$formulario_controlador->set_configuracion($config);
	$formulario_controlador->procesar_request();