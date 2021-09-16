<?php

require_once('cambio.php');

/**
 * Cambio para definir los usuarios/encuestados y grupos.
 */

class cambio_255 extends cambio
{
	function get_descripcion()
	{
		return 'Cambio 255: Migraci�n de datos (encuestados, encuestas, tablas auxiliares, etc.) desde 3.1.2 y definici�n de encuestas de graduados.';
	}

	function cambiar()
	{        
        $dir = $this->path_proyecto . '/sql/cambios/3.4.2/';
        
        $sqls = array(
			$dir.'creacion_usuarios/insercion_en_tablas_3_0.sql', //sirve el mismo script que en 3.0.0
            //En el script de migraci�n de forms se agrega la migraci�n de la tabla de 
            //servicios web ya que ese script ya requiere modificaciones            
            $dir.'modelo_forms_y_encuestas/migracion_forms_3_1_2.sql',
            //lo b�sico de la migraci�n es de 3.0.0, se agrega la migraci�n de indicadores y cambian los rangos de encuestas pre-cargadas
            //Las encuestas 1 a 4, si est�n insertadas en las nuevas tablas es porque tuvieron habilitaciones 
            //Las encuestas >4 si las hay, son locales al usuario
            //Las encuestas 1 a 4 se insertar�n con sus nuevos ids en un script posterior. 
            $dir.'modelo_forms_y_encuestas/actualizacion_ids_3_0.sql',//sirve el mismo script que en 3.0.0
            $this->path_proyecto.'/sql/datos/juegos_de_datos/encuestas_graduados/10_encuestas_graduados.sql'
		);
        
		foreach ($sqls as $archivo) {
			$this->ejecutar_archivo($archivo);
		}
	}
}
?>