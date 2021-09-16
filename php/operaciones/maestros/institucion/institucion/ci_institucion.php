<?php
class ci_institucion extends ci_navegacion
{
    function ini__operacion()
    {
        $tabla = $this->dep('datos')->tabla('institucion');

        // Aca hay un temita interesante, supuestamente al importar instituciones puede que la tabla
        // mgi_instituciones tenga varias entradas. Entonces se usa por defecto siempre la primer
        // entrada.
        $sql = "SELECT * FROM mgi_institucion
                ORDER BY institucion
                LIMIT 1;";
        $tabla->persistidor()->cargar_con_sql($sql);
    }

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(toba_ei_formulario $form)
	{
        $resultados = $this->get_institucion();

		if (!empty($resultados)) {
            // Verifico si existe un archivo subido por el usuario
		    $image_file = toba::proyecto()->get_path() . '/www/img/custom_pdf_image.png';
            if (file_exists($image_file)) {
                $resultados[0]['logo'] = 'Existe un archivo previamente seleccionado';
            }

            $form->set_datos($resultados);
		}
	}

	function evt__formulario__modificacion($datos)
	{
        $this->set_institucion($datos);

		// Verifico si se ha seleccionado un logo
		if (isset($datos['logo'])) {
            $nombre_archivo = $datos['logo']['name'];
            $img = toba::proyecto()->get_www_temp($nombre_archivo);

            // Mover los archivos subidos al servidor del directorio temporal PHP a uno propio.
            $destino = toba::proyecto()->get_path() . '/www/img/custom_pdf_image.png';
            move_uploaded_file($datos['logo']['tmp_name'], $destino);
        }
	}
	
	function get_localidad($id) 
	{
		$localidad = toba::consulta_php('consultas_mug')->get_localidades($id);
		return $localidad[0]['nombre'];
	}

	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------
	function evt__guardar()
	{
        $this->dep('datos')->sincronizar();
	}

    //-----------------------------------------------------------------------------------
    //-- Auxiliares - Datos Tabla
    //-----------------------------------------------------------------------------------
    function get_institucion()
    {
        return $this->dep('datos')->tabla('institucion')->get();
    }

    function set_institucion($datos)
    {
        $this->dep('datos')->tabla('institucion')->set($datos);
    }

}
?>