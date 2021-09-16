<?php
/**
 * MGI = Módulo de Gestión Institucional
 */
class consultas_mgi
{
	function get_responsables_academicas_tipos($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
					tipo_responsable_academica,
					nombre,
					descripcion
				FROM
					mgi_responsable_academica_tipo
				$where
		";
		return consultar_fuente($sql);
	}
	
	function get_responsables_academicas($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
					ra.responsable_academica			as responsable_academica,
					ra.nombre							as nombre,
					ra.codigo							as codigo,
					ra.tipo_responsable_academica		as tipo_responsable_academica,
					ra.institucion						as institucion,
					ra.localidad						as localidad,
					ra.calle							as calle,
					ra.numero							as numero,
					ra.codigo_postal					as codigo_postal,
					ra.telefono							as telefono,
					ra.fax								as fax,
					ra.email							as email
				FROM
					mgi_responsable_academica as ra
				$where
				ORDER BY nombre
		;";
		return kolla_db::consultar($sql);
	}
	
	function get_propuestas($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
					propuesta,
					nombre,
					codigo,
					estado,
					CASE WHEN estado = 'A' THEN 'ACTIVO'
						 WHEN estado = 'B' THEN 'BAJA'
					END AS estado_descripcion					
				FROM
					mgi_propuesta
				$where
		";
		return kolla_db::consultar($sql);
	}	

	function get_titulos($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
					titulo,
					nombre,
					nombre_femenino,
					codigo,
					estado,
					CASE WHEN estado = 'A' THEN 'ACTIVO'
						 WHEN estado = 'B' THEN 'BAJA'
					END AS estado_descripcion,
					titulo_araucano
				FROM
					mgi_titulo
				$where
		";
		return kolla_db::consultar($sql);
	}
    
	function get_instituciones_tipos($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
					it.tipo_institucion as tipo_institucion,
					it.nombre as nombre,  
					it.descripcion as descripcion
				FROM
					mgi_institucion_tipo as it
				$where
		";
		return consultar_fuente($sql);
	}
    
    function get_ra_carreras($where)
    {
        $where = isset($where) ? " WHERE ". $where : '';
        $sql = "SELECT  mgi_propuesta_ra.responsable_academica,
                        mgi_propuesta.*
				FROM    mgi_propuesta_ra
                        JOIN mgi_propuesta ON (mgi_propuesta.propuesta = mgi_propuesta_ra.propuesta)
                $where
		";
		return kolla_db::consultar($sql);
    }
    
    function get_ra_titulos($where)
    {
        $where = isset($where) ? " WHERE ". $where : '';
        $sql = "SELECT  mgi_titulo_ra.responsable_academica,
                        mgi_titulo.*
				FROM    mgi_titulo_ra
                        JOIN mgi_titulo ON (mgi_titulo.titulo = mgi_titulo_ra.titulo)
                $where
		";
		return kolla_db::consultar($sql);
    }

	function get_titulos_propuestas($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where." AND " : ' WHERE ';
		$sql = "SELECT
					tp.propuesta		as propuesta,
					t.titulo 			as titulo,
					t.nombre			as nombre,
					t.nombre_femenino	as nombre_femenino,
					t.codigo			as codigo,
					t.estado			as estado,
					CASE WHEN t.estado = 'A' THEN 'ACTIVO'
						 WHEN t.estado = 'B' THEN 'BAJA'
					END AS estado_descripcion					
				FROM
					mgi_titulo_propuesta 	as tp,
					mgi_titulo	 			as t
				$where
				tp.titulo = t.titulo
		";
		return kolla_db::consultar($sql);
	}
	
	function get_institucion($where=null) 
	{
		$where = isset($where) ? " WHERE ".$where : '';
		$sql = "SELECT 
                            i.institucion		as institucion,
                            i.nombre			as nombre,
                            i.nombre_abreviado	as nombre_abreviado,
                            i.tipo_institucion	as tipo_institucion,
                            i.localidad			as localidad,
                            i.calle				as calle,
                            i.numero			as numero,
                            i.codigo_postal		as codigo_postal,
                            i.telefono			as telefono,
                            i.fax				as fax,
                            i.email				as email,
                            i.institucion_araucano	as institucion_araucano
				FROM        mgi_institucion	as i
				$where
                ORDER BY    institucion
		";
		return consultar_fuente($sql);
	}

	function get_institucion_araucano($codigo=null) 
	{
		$where = isset($codigo) ? " WHERE institucion_araucano = ".quote($codigo) : '';
		$sql = "SELECT 
					ai.institucion_araucano		as institucion_araucano,
					ai.nombre					as nombre 
				FROM
					arau_instituciones	as ai
				$where
				ORDER BY nombre
		";
		return consultar_fuente($sql);
	}	

	function get_instituciones_araucano_ra($codigo=null) 
	{
		$where = isset($codigo) ? " WHERE ra_araucano = ".quote($codigo) : '';
		$sql = "SELECT 
					ai.institucion_araucano		as institucion_araucano,
					ai.nombre					as nombre,
					ara.ra_araucano				as ra_araucano,
					ara.institucion_araucano	as institucion_araucano_ra,
					ara.nombre					as nombre_ra
				FROM
					arau_instituciones as ai INNER JOIN arau_responsables_academicas as ara ON (ara.institucion_araucano = ai.institucion_araucano)
				$where
				ORDER BY nombre
		";
		return consultar_fuente($sql);
	}	
	
	function get_ra_araucano($codigo=null) 
	{
		$where = isset($codigo) ? " WHERE ra_araucano = ".quote($codigo) : '';
		$sql = "SELECT 
					ara.ra_araucano									as ra_araucano,
					ara.institucion_araucano						as institucion_araucano,
					ara.nombre										as nombre,
					ai.nombre || '   ----   ' || ara.nombre			as descripcion 
				FROM
					arau_responsables_academicas	as ara
						INNER JOIN arau_instituciones as ai ON (ara.institucion_araucano = ai.institucion_araucano)
				$where
				ORDER BY descripcion
		";
		return consultar_fuente($sql);
	}
	
	function get_ras_aucano_institucion($codigo=null)
	{
		$where = isset($codigo) ? " WHERE ai.institucion_araucano = ".quote($codigo) : '';
		$sql = "SELECT 
					ara.ra_araucano									as ra_araucano,
					ara.institucion_araucano						as institucion_araucano,
					ara.nombre										as nombre,
					ai.nombre || '   ----   ' || ara.nombre			as descripcion 
				FROM
					arau_responsables_academicas	as ara
						INNER JOIN arau_instituciones as ai ON (ara.institucion_araucano = ai.institucion_araucano)
				$where
				ORDER BY descripcion
		";
		return consultar_fuente($sql);
	}	

	function get_titulo_araucano($codigo=null) 
	{
		$where = isset($codigo) ? ' WHERE titulo_araucano = '.quote($codigo) : '';
		
		$sql = "SELECT	arau_titulos.titulo_araucano	AS titulo_araucano,
						arau_titulos.tipo_titulo		AS tipo_titulo,
						CASE 
							WHEN char_length(arau_titulos.nombre) > ".kolla_texto::chars_maximo_combo."
							THEN substring(arau_titulos.nombre FROM 1 FOR ".kolla_texto::chars_maximo_combo." - 3) || '...'
							ELSE arau_titulos.nombre
						END	AS nombre 
				FROM	arau_titulos
				$where
				ORDER BY nombre
				";
				
		return consultar_fuente($sql);
	}

	/*
	Retorna la descripcion de un titulo de araucano dado su codigo
	*/
	function get_desc_titulo_codigo($codigo=null)
	{
		if (isset($codigo)) {
			$where = " WHERE titulo_araucano = '".$codigo."'";
		} else {
			$where = '';
		}
		
		$sql = "SELECT 
					at.titulo_araucano		as titulo_araucano,
					at.tipo_titulo			as tipo_titulo,
					at.nombre				as nombre 
				FROM
					arau_titulos			as at
				$where
				ORDER BY nombre
		;";

		$resultado = consultar_fuente($sql);
		if (!empty($resultado)) {
			return $resultado[0]['nombre'];
		}
	}

	/*
	Retorna la lista de titulos de araucano para combo editable
	*/
	function get_titulos_araucano_para_combo($codigo=null)
	{
		$where = 'WHERE true ';
		if (isset($codigo)) {
			$where .= " AND nombre ILIKE '%".$codigo."%'::text ";
		} 
		$sql = "SELECT 
					at.titulo_araucano		as titulo_araucano,
					at.tipo_titulo			as tipo_titulo,
					at.nombre				as nombre 
				FROM
					arau_titulos			as at
				$where
				ORDER BY nombre
		;";
		
		$resultado = consultar_fuente($sql);
		if (!empty($resultado)) {
			return $resultado;
		}
	}
	
	function get_conexiones_ws($where=null)
	{	
        if (is_null($where) ) {
            $where = 'TRUE';
        }
		$sql = "SELECT  *
                FROM    sge_ws_conexion 
                WHERE   $where
            ";
        
		return kolla_db::consultar($sql);
	}
    
    function get_ws_combo($ug)
	{	
        $ug = kolla_db::quote($ug);
        
		$sql = "SELECT  *
                FROM    sge_ws_conexion 
                WHERE   unidad_gestion = $ug
            ";
        
		return kolla_db::consultar($sql);
	}
	
	/**
	 * Valida si un nombre de responsable académica se puede usar o no.
	 */
	function validar_nombre_responsable_academica($nombre, $responsable_academica)
	{
		if (!isset($nombre)) {
			return false;
		}
		
		$nombre = kolla_db::quote($nombre);
		$sql = 'SELECT	COUNT(mgi_responsable_academica.responsable_academica) AS cant
                FROM 	mgi_responsable_academica
				WHERE 	'.kolla_sql::armar_condicion_compara_cadenas('mgi_responsable_academica.nombre', $nombre).'
				';
		
		if (!is_null($responsable_academica)) {
			$responsable_academica = kolla_db::quote($responsable_academica);
			$sql .= " AND mgi_responsable_academica.responsable_academica <> $responsable_academica ";
		}
		
		$res = kolla_db::consultar_fila($sql);		
		return $res['cant'] == 0;
	}
	
}
?>