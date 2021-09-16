<?php

class consultas_toba
{
	function get_grupos_acceso($proyecto = null)
	{
		$where = isset($proyecto) ? ' WHERE proyecto = ' . quote($proyecto) : " WHERE proyecto = 'kolla' ";
		$sql = "SELECT 
					uga.proyecto 			AS proyecto,
  					uga.usuario_grupo_acc 	AS usuario_grupo_acc, 
  					uga.nombre 				AS nombre,
  					uga.descripcion 		AS descripcion
  				FROM 
					apex_usuario_grupo_acc uga
				$where
				";

		return toba::instancia()->get_db()->consultar($sql);
	}

	function get_perfiles_funcionales_usuario($usuario = null)
	{
		if (isset($usuario)) {
			$where = " AND au.usuario = '" . $usuario . "'";
		}
		$sql = "SELECT 
					au.usuario, 
					au.nombre,
					aup.*
				FROM 
					apex_usuario au INNER JOIN apex_usuario_proyecto aup ON (au.usuario = aup.usuario)
				WHERE
					aup.proyecto = 'kolla' $where";
        
		return toba::instancia()->get_db()->consultar($sql);
	}

	function get_lista_usuarios($filtro = null)
	{
		$id_proyecto = toba::proyecto()->get_id();
		$condiciones = array();
		
		if (isset($filtro)) {
			if (isset($filtro['apellido_nombre'])) {
				$quote = quote("%{$filtro['apellido_nombre']}%");
				$condiciones[] = "(au.nombre ILIKE $quote)";
			}
			if (isset($filtro['usuario'])) {
				$quote = quote("%{$filtro['usuario']}%");
				$condiciones[] = "(au.usuario ILIKE $quote)";
			}
			if (isset($filtro['tipo'])) {
				switch ($filtro['tipo']) {
                    case 'E':
                        $condiciones[] = "(aup.usuario_grupo_acc = 'encuesta')";
                        break;
                    case 'A':
                        $condiciones[] = "(aup.usuario_grupo_acc = 'admin')";
                        break;
                    case 'G':
                        $condiciones[] = "(aup.usuario_grupo_acc = 'guest')";
                        break;
                    case 'X':
                        $condiciones[] = "(aup.usuario_grupo_acc = 'externo')";
                        break;
				}
			}
		}
		
		if ($condiciones) {
			$where = ' AND ' . implode(' AND ', $condiciones);
		}

		$sql = "
			SELECT
				au.usuario AS usuario,
				au.nombre AS nombre,
				aup.usuario_grupo_acc AS usuario_grupo_acc,
				uga.nombre AS nombre_grupo
			FROM
				apex_usuario au 
				INNER JOIN apex_usuario_proyecto aup ON (au.usuario = aup.usuario)
					INNER JOIN apex_usuario_grupo_acc uga 
						ON (uga.usuario_grupo_acc = aup.usuario_grupo_acc) AND (uga.proyecto = aup.proyecto)
			WHERE
				aup.proyecto = '" . $id_proyecto . "'
				$where
			ORDER BY 
				usuario
		";
		
		return toba::instancia()->get_db()->consultar($sql);
	}
    
    function get_perfiles_datos($where='')
	{
		if ($where) {
			$where = "AND $where";
		}
		$sql = "SELECT	apex_usuario_perfil_datos.usuario_perfil_datos,
						apex_usuario_perfil_datos.nombre
				FROM	apex_usuario_perfil_datos
				WHERE	apex_usuario_perfil_datos.proyecto = '".toba::proyecto()->get_id()."'
						$where
		";
		return toba::instancia()->get_db()->consultar($sql);
	}
	
}
?>