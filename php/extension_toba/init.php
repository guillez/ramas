<?php

class init 
{
	static private $mapeo_archivos;
	
	static function inicializar()
	{
		self::$mapeo_archivos = self::get_mapeo_archivos();
		spl_autoload_register(array('init', 'cargador_clases'));

		//Definicion de constantes
		define("URL_ATENCION_USUARIOS", "http://comunidad.siu.edu.ar/");
		define("URL_EXTRANET", "http://extranet.siu.edu.ar/");
		//define("URL_CAMBIOSVERSION", "http://web.siu.edu.ar/servicios/?ai=servicios||40000109");
		//Nueva versión de Proyecto Servicios 
		define("URL_CAMBIOSVERSION", "http://documentacion.siu.edu.ar/wiki/SIU-Kolla/cambios_por_version");
        define("URL_WIKIKOLLA", "http://documentacion.siu.edu.ar/wiki/SIU-Kolla");
        define("URL_TOBA_USUARIOS", toba_parametros::get_redefinicion_parametro('toba_usuarios', 'url', false));
		define("ITEM_INICIO", 2);
		define("REPORTE_ENCUESTADO", 1);
		define("ITEM_REPORTE_ENCUESTADO", 40000125);
		define("REPORTE_PREGUNTA", 2);
		define("ITEM_REPORTE_PREGUNTA", 40000126);
		define("REPORTE_MULTIPLES", 3);
		define("ITEM_REPORTE_MULTIPLES", 40000132);
		define("ITEM_RESPONDER_ENCUESTA_EXTERNA", 40000112);
		define("ITEM_REPORTE_FORMS_ENCUESTADO", 46000018);
        
		define("REPORTE_PREGUNTA_HABILITACION", 4);
		define("REPORTE_RESPUESTAS_HABILITACION", 5);
        define("REPORTE_ENCUESTADO_HABILITACION", 6);
        define("RESULTADOS_ENCUESTADO", 10);
        define("RESULTADOS_PREGUNTA", 11);
        define("RESULTADOS_CONTEO_RESPUESTAS", 12);

	}

	static function cargador_clases($nombre_clase) 
	{
		if (isset(self::$mapeo_archivos[$nombre_clase])) {
			require_once(self::$mapeo_archivos[$nombre_clase]);
		}
	}

	static function get_mapeo_archivos() 
	{
		return array(
			
			//-- Componentes Formulario
			'kolla_componente_textarea'				=> 'nucleo/componentes/kolla_componente_textarea.php',
			'kolla_componente_texto'				=> 'nucleo/componentes/kolla_componente_texto.php',
			'kolla_componente_tfecha'				=> 'nucleo/componentes/kolla_componente_tfecha.php',
			'kolla_componente_tmail'				=> 'nucleo/componentes/kolla_componente_tmail.php',
			'kolla_componente_tnumeroanio'      	=> 'nucleo/componentes/kolla_componente_tnumeroanio.php',
			'kolla_componente_tnumerodecimal'   	=> 'nucleo/componentes/kolla_componente_tnumerodecimal.php',
			'kolla_componente_tnumeroedad'     		=> 'nucleo/componentes/kolla_componente_tnumeroedad.php',
			'kolla_componente_tnumeroentero'    	=> 'nucleo/componentes/kolla_componente_tnumeroentero.php',
			'kolla_componente_encuesta'				=> 'nucleo/componentes/kolla_componente_encuesta.php',
			'kolla_componente_combo'            	=> 'nucleo/componentes/kolla_componente_combo.php',
			'kolla_componente_check'            	=> 'nucleo/componentes/kolla_componente_check.php',
			'kolla_componente_localidad'        	=> 'nucleo/componentes/kolla_componente_localidad.php',
			'kolla_componente_list'					=> 'nucleo/componentes/kolla_componente_list.php',
			'kolla_componente_radio'				=> 'nucleo/componentes/kolla_componente_radio.php',
			'kolla_formulario_boton'				=> 'nucleo/componentes/kolla_formulario_boton.php',
			'kolla_comp_encuesta'           	    => 'nucleo/formulario/componentes/kolla_comp_encuesta.php',
			'kolla_comp_opciones'      		     	=> 'nucleo/formulario/componentes/kolla_comp_opciones.php',
			'kolla_cp_check'						=> 'nucleo/formulario/componentes/kolla_cp_check.php',
			'kolla_cp_radio'						=> 'nucleo/formulario/componentes/kolla_cp_radio.php',
			'kolla_cp_combo'						=> 'nucleo/formulario/componentes/kolla_cp_combo.php',
			'kolla_cp_list'							=> 'nucleo/formulario/componentes/kolla_cp_list.php',
			'kolla_cp_localidad'					=> 'nucleo/formulario/componentes/kolla_cp_localidad.php',
			'kolla_cp_textarea'						=> 'nucleo/formulario/componentes/kolla_cp_textarea.php',
			'kolla_cp_input'						=> 'nucleo/formulario/componentes/kolla_cp_input.php',
	
			'batcher_respuestas'					=> 'nucleo/formulario/utils/batcher_respuestas.php',
			'repositorio_componentes'				=> 'nucleo/formulario/componentes/repositorio_componentes.php',
			'validador_cmp'							=> 'nucleo/formulario/componentes/validador_cmp.php',
			'anonimato_utils'						=> 'nucleo/formulario/utils/anonimato_utils.php',
			'kolla'									=> 'nucleo/formulario/kolla.php',
				
            'catalogo'								=> 'nucleo/formulario/bd/catalogo.php',
			'catalogable'							=> 'nucleo/formulario/bd/catalogo.php',
			'dao_encuestas'							=> 'nucleo/formulario/bd/dao_encuestas.php', 
			
            'admin_cache'							=> 'nucleo/formulario/utils/cache/admin_cache.php',
			'cache_memoria_memcached'				=> 'nucleo/formulario/utils/cache/cache_memoria_memcached.php',
            'cache_memoria_apc'						=> 'nucleo/formulario/utils/cache/cache_memoria_apc.php',
            'cache'									=> 'nucleo/formulario/utils/cache/cache.php',
            'formulario_localidad'					=> 'nucleo/formulario/vista/formulario_localidad.php',
			'validador'								=> 'modelo/comunes/validador.php',
				
			//-- Nucleo_formulario -> módulo aislado
			'formulario_vista'						=> 'nucleo/formulario/formulario_vista.php',
			'formulario_controlador'				=> 'nucleo/formulario/formulario_controlador.php',
			'formulario'							=> 'nucleo/formulario/formulario.php', 
			
			//-- Reportes
			'reportes_kolla'						=> 'nucleo/reportes/reportes_kolla.php',
			'reportes_forms'						=> 'nucleo/reportes/reportes_forms.php',
		
			//-- nuSoap para webservices 
			'nusoap_client'							=> 'nucleo/lib/nusoap/nusoap.php',
		
			//-- conexion a webservice server
			'url_encuestas'							=> 'nucleo/lib/url_encuestas.php',
			'ws_encuestas_respondidas'				=> 'nucleo/servicios_web/kolla/ws_encuestas_respondidas.php',
			'ws_habilitar'							=> 'nucleo/servicios_web/kolla/ws_habilitar.php',
		
			//-- Clases útiles de librería
			'kolla_arreglos'						=> 'nucleo/lib/kolla_arreglos.php',
			'kolla_texto'							=> 'nucleo/lib/kolla_texto.php',
			
			//-- Tipos pagina
			'kolla_tp_basico'						=> 'extension_toba/tipos_pagina/kolla_tp_basico.php',
		
			//-- Utilidades
			'kolla_logs'							=>	'nucleo/lib/utilidades/kolla_logs.php',
			'kolla_logs_resultados'					=>	'nucleo/lib/utilidades/kolla_logs_resultados.php',
			'kolla_logs_estados'					=>	'nucleo/lib/utilidades/kolla_logs_estados.php',
		
			//-- Procesos
			'kolla_procesos_bk'						=> 'nucleo/lib/procesos_bk/kolla_procesos_bk.php',
			'invocador_procesos_bk'					=> 'nucleo/lib/procesos_bk/invocador_procesos_bk.php',
			
			//-- Modelo
			'encuesta'								=> 'modelo/encuesta/encuesta.php',
			'bloque'								=> 'modelo/encuesta/bloque.php',
			'abm'									=> 'modelo/comunes/abm.php',
			'kolla_sql'								=> 'modelo/comunes/kolla_sql.php',
			'kolla_fecha'							=> 'modelo/comunes/kolla_fecha.php',
		
			//-- Clases útiles para la navegación de operaciones
			'ci_navegacion'							=> 'operaciones/lib/ci_navegacion.php',
			'ci_navegacion_cn'						=> 'operaciones/lib/ci_navegacion_cn.php',
		
			//Extensión para el controlador de negocios
			'cn_entidad'							=> 'nucleo/lib/cn_entidad.php',
            'kolla_url'                             => 'nucleo/lib/kolla_url.php',
            'kolla_db'                              => 'nucleo/lib/kolla_db.php',
            
            //REST
            'rest_base'						        => 'nucleo/rest/rest_base.php',
            'rest_conceptos'						=> 'nucleo/conceptos/rest_conceptos.php',
            'rest_elementos'						=> 'nucleo/elementos/rest_elementos.php',
            'rest_tipo_elementos'					=> 'nucleo/tipo_elementos/rest_tipo_elementos.php',
            'rest_habilitaciones'					=> 'nucleo/habilitaciones/rest_habilitaciones.php',
            'rest_encuestas'                        => 'nucleo/encuesta/rest_encuestas.php',
            'guarani'                               => 'nucleo/guarani/guarani.php',
            'importador_ws'                         => 'nucleo/servicios_web/importadores/importador_ws.php',
            'importador_usuarios_ws'                => 'nucleo/servicios_web/importadores/importador_usuarios_ws.php',
            'importador_institucion_ws'             => 'nucleo/servicios_web/importadores/importador_institucion_ws.php',
            'importador_usuarios_archivo'           => 'nucleo/servicios_web/importadores/importador_usuarios_archivo.php',
			
			//Clases de actualización
			'act_encuestas'  						=> 'nucleo/encuesta/act_encuestas.php',
            'act_conceptos'  						=> 'nucleo/conceptos/act_conceptos.php',
            'act_elementos'  						=> 'nucleo/elementos/act_elementos.php',
            'act_tipo_elementos'					=> 'nucleo/tipo_elementos/act_tipo_elementos.php',
            'act_habilitaciones'					=> 'nucleo/habilitaciones/act_habilitaciones.php',

            // Toba
            'act_toba'        						=> 'nucleo/toba/act_toba.php',
            
            // Preguntas dependientes
            'pregunta_dependencias'                 => 'nucleo/preguntas/pregunta_dependencias.php',
            'pregunta_dependencia_def'              => 'nucleo/preguntas/pregunta_dependencia_def.php',
            'pregunta_condicion'                    => 'nucleo/preguntas/condiciones/pregunta_condicion.php',
            'pregunta_condicion_fecha'              => 'nucleo/preguntas/condiciones/pregunta_condicion_fecha.php',
            'pregunta_condicion_fecha_entre'        => 'nucleo/preguntas/condiciones/pregunta_condicion_fecha_entre.php',
            'pregunta_condicion_numero_entre'       => 'nucleo/preguntas/condiciones/pregunta_condicion_numero_entre.php',
            'pregunta_condicion_cadena'             => 'nucleo/preguntas/condiciones/pregunta_condicion_cadena.php',
            'pregunta_condicion_lista'              => 'nucleo/preguntas/condiciones/pregunta_condicion_lista.php',
            'pregunta_condicion_booleano'           => 'nucleo/preguntas/condiciones/pregunta_condicion_booleano.php',
            'pregunta_base'                         => 'nucleo/preguntas/operadores/pregunta_base.php',
            'pregunta_opciones'                     => 'nucleo/preguntas/operadores/pregunta_opciones.php',
            'pregunta_opciones_lista'               => 'nucleo/preguntas/operadores/pregunta_opciones_lista.php',
            'pregunta_booleano'                     => 'nucleo/preguntas/operadores/pregunta_booleano.php',
            'pregunta_cadena'                       => 'nucleo/preguntas/operadores/pregunta_cadena.php',
            'pregunta_numero'                       => 'nucleo/preguntas/operadores/pregunta_numero.php',
            'pregunta_fecha'                        => 'nucleo/preguntas/operadores/pregunta_fecha.php',
            
            // Tablas asociadas
            'act_tablas_asociadas'                  => 'nucleo/tablas_asociadas/act_tablas_asociadas.php',
		);
	}
}