<?php

class kolla_ejecucion implements toba_interface_contexto_ejecucion 
{ 
	function conf__inicial()
	{
		if (isset($_GET[apex_sesion_qs_finalizar]) && ($_GET[apex_sesion_qs_finalizar] == 1)) {
			session_destroy();
			//toba::vinculador()->navegar_a(toba::proyecto()->get_id(), 200000004);
            toba::vinculador()->navegar_a(toba::proyecto()->get_id(), 12000094);
		}
        
        // Si estoy en desarrollo que tenga habilitado el control de sincronización de svn
		if ( !toba::instalacion()->es_produccion() && !toba::instalacion()->chequea_sincro_svn() ) {
            $mensaje = "
                <p>Falta el parámetro 'chequea_sincro_svn = 1' en el archivo instalacion.ini de la instalación de Toba. Siga los siguientes pasos para corregir esto:</p>
                
                <ol>
                    <li>Edite el archivo instalacion.ini y agregue <pre>chequea_sincro_svn = 1</pre></li>
                    <li>Ejecute <pre>toba proyecto exportar</pre></li>
                    <li>Realice un update de SVN del proyecto verificando que no existen conflictos</li>
                    <li>Ejecute <pre>toba proyecto regenerar</pre></li>
                </ol>
            ";
            
			throw new toba_error($mensaje);
		}
		
		require_once('extension_toba/init.php');
		init::inicializar();
	}

	function conf__final() {}

}
?>