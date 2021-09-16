<?php
use ext_bootstrap\componentes\bootstrap_ci;

class ci_obtener_archivo extends bootstrap_ci
{
	private $path_reportes;
	protected $id_reporte;
	
	function ini() 
	{ 
		//se determina si se viene del menú o si se trae como parametro de la operacion de exportar el id de reporte
		if (!toba::memoria()->verificar_acceso_menu()) {
			$this->id_reporte = toba::memoria()->get_parametro('id_reporte');	
		}
		//se setea el path a los archivos
		$this->path_reportes = toba::proyecto()->get_path() . "/procesos/reportes/";
	}
	
	//-----------------------------------------------------------------------------------
	//---- form_reporte -----------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__form_reporte(toba_ei_formulario $form)
	{
		if (isset($this->id_reporte)) {
			$form->set_datos(array('id_reporte' =>$this->id_reporte));
		}
	}
	
	
	function evt__form_reporte__obtener($datos)
	{
		//con el codigo ingresado obtengo los datos del archivo generado
		$id_reporte = $datos['id_reporte'];
		$nombre = toba::consulta_php('consultas_reportes')->obtener_nombre_archivo_forms($id_reporte);

		if ( !$nombre ) {
			toba::notificacion()->agregar('Código inexistente.', 'error');
			return false;
		}
			
		//si no hay nombre es que el proceso no termino aun
		if ($nombre == '') {
			toba::notificacion()->agregar('El proceso no ha terminado aún (o se produjo algún error al generar el reporte), 
											reintente en unos minutos por favor.', 'info');
		} else {
			//si hay nombre, chequeo que el archivo exista y levanto sus contenidos
			$file = $this->path_reportes . $nombre . ".txt"; 
			if (file_exists($file)) {
				toba::memoria()->set_dato('nombre_archivo', $nombre);
				toba::vinculador()->navegar_a(null,'40000129'); //bajar archivo	
			} else {
				toba::notificacion()->agregar('El archivo no fue creado o ha sido borrado.', 'info');
			}
		}
	}
}
?>