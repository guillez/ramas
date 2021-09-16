<?php
/**
 * Invocador de procesos
 */
class invocador_procesos_bk
{
	static protected $instancia;

	static function instancia()
	{
		if (!isset(self::$instancia)) {
			self::$instancia = new invocador_procesos_bk();
		}
		return self::$instancia;	
	}

	public function __construct()
	{
	
	}
	
	public function ejecutar_proceso($proceso)
	{
		// Serializo el Proceso
		$proc_serializado = serialize($proceso);
		// Se guarda el proceso serializado con path que se genero en la creacion del porceso
		$path_procesos_serializado = $proceso->get_path_proceso_serializado();
		toba_manejador_archivos::crear_archivo_con_datos($path_procesos_serializado, $proc_serializado);
		// Se dispara el item de consola con el ID

		if ( toba_manejador_archivos::es_windows() ) {
			$script = 'script_proceso.bat';
		} else {
			$script = 'script_proceso.sh';
		}
		$path_script        = toba::proyecto()->get_path_php() . "/nucleo/lib/procesos_bk/";
		$path_toba          = toba_dir();
		$path_temp          = toba::proyecto()->get_path_temp();
		$path_instalacion   = toba::nucleo()->toba_instalacion_dir();
		$instancia          = toba::instancia()->get_id();

		$comando = $path_script . "$script $path_toba $path_temp $path_instalacion $instancia 38000155 $path_procesos_serializado";

		toba_manejador_procesos::background($comando);

	}
	
}
?>