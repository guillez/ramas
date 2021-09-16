<?php
class ci_imagen_de_perfil extends ci_navegacion
{
	//-----------------------------------------------------------------------------------
	//---- Eventos ----------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function evt__procesar()
	{
	}

	function evt__cancelar()
	{
	    // Este evento se utiliza para restaurar a los valores por defecto.
        // Esto se logra poniendo en null los valores de la imagen de perfil en la BD (nombre y bytes)
        $datos_usuario = toba::consulta_php('consultas_usuarios')->get_datos_encuestado_x_usuario_sin_documento(\toba::usuario()->get_id());
        $sql = "UPDATE sge_encuestado 
                    SET imagen_perfil_nombre = null,
                    imagen_perfil_bytes = null
                    WHERE encuestado = {$datos_usuario['encuestado']}";
        kolla_db::ejecutar($sql);

        \toba::memoria()->set_dato_instancia("flag_imagen_perfil", true);

        // Recargo la página porque el menu se carga antes de enterarse que cambió el flag
        \toba::vinculador()->navegar_a('kolla','44000002');
	}

	//-----------------------------------------------------------------------------------
	//---- formulario -------------------------------------------------------------------
	//-----------------------------------------------------------------------------------

	function conf__formulario(ei_formulario_modificado_para_imagen_perfil $form)
	{
	}

	function evt__formulario__modificacion($datos)
	{
        $datos_ususario = toba::consulta_php('consultas_usuarios')->get_datos_encuestado_x_usuario_sin_documento(\toba::usuario()->get_id());

        // Verifico si se ha seleccionado un logo
        if (isset($datos['imagen_de_perfil'])) {

            $size_in_megas = $datos['imagen_de_perfil'][size] / 1024 / 1024;

            if ($size_in_megas > 2)
            {
                toba::notificacion()->agregar('No es posible utilizar esta imagen porque supera los 2MB de tamaño', 'error');
            } else
            {
                $nombre_archivo = kolla_db::quote($datos['imagen_de_perfil']['name']);
                $bytes = pg_escape_bytea(file_get_contents($datos['imagen_de_perfil']['tmp_name']));

                $sql = "UPDATE sge_encuestado 
                    SET imagen_perfil_nombre = {$nombre_archivo},
                    imagen_perfil_bytes = '{$bytes}'
                    WHERE encuestado = {$datos_ususario['encuestado']}";
                kolla_db::ejecutar($sql);

                \toba::memoria()->set_dato_instancia("flag_imagen_perfil", true);

                // Recargo la página porque el menu se carga antes de enterarse que cambió el flag
                \toba::vinculador()->navegar_a('kolla','44000002');
            }
        }
	}

}
?>