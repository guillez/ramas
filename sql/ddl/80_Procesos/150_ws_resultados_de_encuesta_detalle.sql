CREATE OR REPLACE FUNCTION ws_resultados_de_encuesta_detalle(hab integer, form_hab character varying, elem character varying, preg integer)
  RETURNS SETOF record AS
$BODY$

DECLARE 
tabla record;
cond_preg character varying;
cond_elem character varying;
BEGIN

IF preg is not null THEN cond_preg := ' encuesta.pregunta = ' || preg; ELSE cond_preg := ' true '; END IF;
IF (elem is not null and elem <> '') THEN cond_elem := ' se.elemento_externo = ' || quote_literal(elem)  ; ELSE cond_elem := ' true '; END IF;

RETURN QUERY 
select 
	sed.encuesta_definicion, --0
	sb.bloque, --1
	sp.pregunta as pregunta_id, --2
	sp.nombre as pregunta_texto, --3
	scp.componente, --4
	CASE WHEN (scp.tipo = 'A') THEN 'S' ELSE 'N' END AS es_libre, --5
	CASE WHEN (scp.numero = 4) OR (scp.numero = 5) THEN 'S'ELSE 'N' END AS es_multiple,--6
	sed.obligatoria, --7
	sb.orden as bloque_orden, --8
	sed.orden as pregunta_orden_bloque, --9
	sb.orden || '_' || sed.orden as orden_en_encuesta, --10
	
	CASE
		WHEN (scp.tipo = 'A') THEN null
		WHEN (scp.tipo = 'E' AND scp.componente <> 'label' AND srd2.respuesta_valor <> '') THEN srd2.respuesta_valor::integer
		WHEN (scp.tipo = 'C') THEN sr.respuesta
	END AS respuesta_id, --11	
	spr.orden::character varying as repuesta_orden,--12
	CASE
		WHEN (scp.tipo = 'A') THEN srd2.respuesta_valor
		WHEN (scp.tipo = 'E' and scp.componente <> 'label' AND srd2.respuesta_valor <> '') THEN (SELECT nombre FROM mug_localidades WHERE localidad = srd2.respuesta_valor::integer )		
		WHEN (scp.tipo = 'C') THEN sr.valor_tabulado
	END AS respuesta_valor, --13
	CASE
		WHEN (scp.tipo = 'A' OR scp.tipo = 'E' ) THEN count(srd2.respuesta_valor)
		ELSE count(srd.respuesta_codigo)
	END AS elegida_cantidad --14
	
FROM sge_habilitacion sh  
	INNER JOIN sge_formulario_habilitado sfh ON (sh.habilitacion = sfh.habilitacion)
	LEFT JOIN sge_concepto sc on (sc.concepto = sfh.concepto)
	INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado)
	INNER JOIN sge_encuesta_atributo sea ON (sea.encuesta = sfhd.encuesta)
	LEFT JOIN sge_elemento se ON (se.elemento = sfhd.elemento)
	INNER JOIN sge_encuesta_definicion sed ON (sea.encuesta = sed.encuesta)
	INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
	INNER JOIN sge_pregunta sp ON (sp.pregunta = sed.pregunta  and (sp.tabla_asociada = '' OR sp.tabla_asociada is NULL) )
	INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
	left JOIN sge_pregunta_respuesta spr on (spr.pregunta = sp.pregunta )
	left JOIN sge_respuesta sr on (sr.respuesta = spr.respuesta) 
	LEFT JOIN sge_respondido_formulario srf ON (srf.formulario_habilitado = sfh.formulario_habilitado)
	LEFT JOIN sge_respondido_encuesta sre ON (sre.respondido_formulario = srf.respondido_formulario AND sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
	LEFT JOIN sge_respondido_detalle srd ON (srd.respondido_encuesta = sre.respondido_encuesta 
				and srd.encuesta_definicion = sed.encuesta_definicion and srd.respuesta_codigo = sr.respuesta)
	LEFT JOIN sge_respondido_detalle srd2 ON (srd2.respondido_encuesta = sre.respondido_encuesta 
					and srd2.encuesta_definicion = sed.encuesta_definicion )
WHERE 
	sh.habilitacion = hab AND 
	sfh.formulario_habilitado_externo = form_hab  
	AND CASE WHEN elem is not null THEN se.elemento_externo = elem ELSE true end
	AND CASE WHEN preg is not null THEN sp.pregunta = preg ELSE true end

GROUP BY sed.encuesta_definicion,sb.bloque, sp.pregunta, scp.componente, scp.tipo, scp.numero, sed.obligatoria, sed.orden, sr.respuesta,spr.orden, srd.respuesta_valor,srd2.respuesta_valor,sr.valor_tabulado;

FOR tabla IN 
	SELECT DISTINCT sp.pregunta, sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion, sp.tabla_asociada_orden_campo, sp.tabla_asociada_orden_tipo
	FROM sge_formulario_habilitado sfh 
		INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
		INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta)
		INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	WHERE sfh.habilitacion = hab AND sfh.formulario_habilitado_externo = form_hab AND sp.tabla_asociada != ''
        AND CASE WHEN preg is not null THEN sp.pregunta = preg ELSE true END
LOOP
	RETURN QUERY EXECUTE 'select 
				encuesta.encuesta_definicion, --0
				encuesta.bloque, --1
				encuesta.pregunta as pregunta_id, --2
				encuesta.pregunta_nombre as pregunta_texto, --3
				encuesta.componente, --4
				CASE WHEN (encuesta.tipo = ''A'') THEN ''S'' ELSE ''N'' END AS es_libre, --5
				CASE WHEN (encuesta.numero = 4) OR (encuesta.numero = 5) THEN ''S'' ELSE ''N'' END AS es_multiple,--6
				encuesta.obligatoria, --7
				encuesta.bloque_orden, --8
				encuesta.sed_orden as pregunta_orden_bloque, --9
				encuesta.bloque_orden || ''_'' || encuesta.preg_orden as orden_en_encuesta, --10
				encuesta.rta_codigo AS respuesta_id, --11
				encuesta.rta_orden::character varying  as repuesta_orden,--12
				encuesta.rta_descripcion::character varying AS respuesta_valor--, --13
				,count(srd.respuesta_codigo) AS elegida_cantidad --14
			FROM 
				(
					SELECT 
						sed.encuesta_definicion, 
						sb.bloque, 
						sp.pregunta, 
						scp.componente, 
						scp.tipo, 
						scp.numero, 
						ta.' || tabla.tabla_asociada_codigo || ' , 
						sea.encuesta, 
						sed.orden as sed_orden, 
						sed.obligatoria, 
						sb.orden as bloque_orden, 
						sed.orden as preg_orden, 
						sp.tabla_asociada, 
						sp.nombre as pregunta_nombre, 
						ta.' || tabla.tabla_asociada_codigo || '  as rta_codigo, 
						ta.' ||	CASE when (tabla.tabla_asociada_orden_campo = 'codigo') THEN tabla.tabla_asociada_codigo ELSE tabla.tabla_asociada_descripcion END || ' as rta_orden,
						ta.' || tabla.tabla_asociada_descripcion || ' as rta_descripcion
					FROM sge_encuesta_atributo sea 	INNER JOIN sge_encuesta_definicion sed ON (sea.encuesta = sed.encuesta)
									INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
									INNER JOIN sge_pregunta sp ON (sp.pregunta = sed.pregunta AND sp.pregunta = ' || tabla.pregunta || ' AND sp.tabla_asociada = ''' || tabla.tabla_asociada || ''' )
									INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero), 
									' || tabla.tabla_asociada || ' ta
				) as encuesta 
				INNER JOIN sge_formulario_habilitado_detalle sfhd ON (encuesta.encuesta = sfhd.encuesta)
				INNER JOIN sge_formulario_habilitado sfh ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
				INNER JOIN sge_habilitacion sh ON (sh.habilitacion = sfh.habilitacion) 
				LEFT JOIN sge_elemento se on (se.elemento = sfhd.elemento)
				LEFT JOIN sge_respondido_formulario srf ON (srf.formulario_habilitado = sfh.formulario_habilitado)
				LEFT JOIN sge_respondido_encuesta sre ON (sre.respondido_formulario = srf.respondido_formulario AND sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
				left join sge_respondido_detalle srd on (srd.respondido_encuesta = sre.respondido_encuesta and srd.encuesta_definicion = encuesta.encuesta_definicion and srd.respuesta_codigo = encuesta.rta_codigo)				
			where sh.habilitacion = ' || hab || ' and 
				encuesta.tabla_asociada = ''' || tabla.tabla_asociada || ''' and
				sfh.formulario_habilitado_externo = ''' || form_hab || '''  and 
				'|| cond_elem ||' and '|| cond_preg ||'
				
			GROUP BY encuesta.encuesta_definicion, encuesta.bloque, encuesta.pregunta, encuesta.pregunta_nombre, encuesta.componente, encuesta.tipo, encuesta.numero, encuesta.obligatoria, 
				encuesta.bloque_orden,encuesta.sed_orden,encuesta.preg_orden,encuesta.rta_codigo,encuesta.rta_orden, encuesta.rta_descripcion;
			' ;
END LOOP;

RETURN;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
