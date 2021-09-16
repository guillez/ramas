CREATE OR REPLACE FUNCTION ws_resultados_de_encuesta_resumen(hab integer, form_hab character varying, enc integer, elem character varying)
  RETURNS SETOF record AS
$BODY$
DECLARE 
tabla record;

BEGIN

RETURN QUERY 

--1) PREGUNTAS CERRADAS DE RESPUESTA TABULADA
SELECT DISTINCT
    sea.encuesta,
    sea.nombre,
    sea.descripcion,
    
    sea.texto_preliminar,
    sea.implementada,
    sea.estado,
    sea.unidad_gestion,

    sb.bloque,
    sb.nombre as bloque_nombre,
    sb.descripcion as bloque_descripcion,
    sb.orden as bloque_orden,

    sed.encuesta_definicion,
    sp.pregunta,
    sp.nombre as pregunta_nombre,
    sp.componente_numero,
    sp.tabla_asociada,
    sp.tabla_asociada_codigo,
    sp.tabla_asociada_descripcion,
    sp.tabla_asociada_orden_campo,
    sp.tabla_asociada_orden_tipo,
    CASE 
        WHEN scp.tipo = 'A' THEN 'S'
        ELSE 'N'
    END as es_libre,
    CASE 
        WHEN sp.componente_numero IN (2,4,5) THEN 'S'
        ELSE 'N'
    END as es_multiple,
    
    sed.obligatoria, 
    sed.orden as pregunta_orden,
    
    scp.componente,

    count(distinct sropciones.respuesta) as opciones_respuesta_disponible,
    count(distinct srd.respuesta_codigo) as opciones_respuesta_elegidas
FROM 
--ARMADO DEL CONTENIDO DE LA ENCUESTA
	sge_encuesta_atributo sea --ENCUESTA
	INNER JOIN sge_encuesta_definicion sed on (sea.encuesta = sed.encuesta) --DEFINICIÓN DE ENCUESTA
	INNER JOIN sge_bloque sb on (sed.bloque = sb.bloque)--BLOQUES 
	INNER JOIN sge_pregunta sp on (sed.pregunta = sp.pregunta AND sea.unidad_gestion = sp.unidad_gestion) --PREGUNTAS 
	INNER JOIN sge_componente_pregunta scp on (scp.numero = sp.componente_numero) --COMPONENTES
--OPCIONES DE RESPUESTA CONTENIDAS EN LA ENCUESTA
	INNER JOIN sge_pregunta_respuesta spr ON (spr.pregunta = sp.pregunta)
	INNER JOIN sge_respuesta sropciones ON (spr.respuesta = sropciones.respuesta) --SI ES RESPUESTA TABULADA ESTÁ EN LA TABLA SGE_RESPUESTAS
--RECUPERACION DE RESPUESTAS RECIBIDAS A LA ENCUESTA
	INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.encuesta = sea.encuesta) --ENCUESTA EN FORMULARIO HABILITADO
	INNER JOIN sge_formulario_habilitado sfh ON (sfhd.formulario_habilitado = sfh.formulario_habilitado) --FORMULARIO HABILITADO
	LEFT JOIN sge_respondido_detalle srd ON (srd.encuesta_definicion = sed.encuesta_definicion) -- DETALLE DE PREGUNTA EN RESPUESTA REGISTRADA
	LEFT JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta 
						AND sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle) --DETALLE DE ENCUESTA EN RESPUESTA REGISTRADA
	LEFT JOIN sge_respondido_formulario srf ON (srf.respondido_formulario = sre.respondido_formulario) --DETALLE DEL FORMULARIO EN RESPUESTA REGISTRADA

	LEFT JOIN sge_respuesta srelegidas ON (srd.respuesta_codigo = srelegidas.respuesta) --SI ES RESPUESTA TABULADA COINCIDE CON ALGUN REGISTRO EN SGE_RESPUESTA
	LEFT JOIN sge_elemento se ON (sfhd.elemento = se.elemento)
where sea.encuesta = enc AND sfh.habilitacion = hab AND sfh.formulario_habilitado_externo = form_hab AND se.elemento_externo = elem 
	AND (sp.tabla_asociada is null OR sp.tabla_asociada = '') AND (scp.tipo = 'C')
group by sea.encuesta, sb.bloque, sed.encuesta_definicion, sp.pregunta, sed.obligatoria, sed.orden, scp.componente, scp.tipo

UNION

--2) PREGUNTAS DE LOCALIDAD
SELECT DISTINCT
    sea.encuesta,
    sea.nombre,
    sea.descripcion,
    
    sea.texto_preliminar,
    sea.implementada,
    sea.estado,
    sea.unidad_gestion,

    sb.bloque,
    sb.nombre as bloque_nombre,
    sb.descripcion as bloque_descripcion,
    sb.orden as bloque_orden,

    sed.encuesta_definicion,
    sp.pregunta,
    sp.nombre as pregunta_nombre,
    sp.componente_numero,
    sp.tabla_asociada,
    sp.tabla_asociada_codigo,
    sp.tabla_asociada_descripcion,
    sp.tabla_asociada_orden_campo,
    sp.tabla_asociada_orden_tipo,
    CASE 
        WHEN scp.tipo = 'A' THEN 'S'
        ELSE 'N'
    END as es_libre,
    CASE 
        WHEN sp.componente_numero IN (2,4,5) THEN 'S'
        ELSE 'N'
    END as es_multiple,
    
    sed.obligatoria, 
    sed.orden as pregunta_orden,
    
    scp.componente,
    (select count (distinct localidad) from mug_localidades) as opciones_respuesta_disponible,
    count(distinct srd.respuesta_valor)as opciones_respuesta_elegidas
FROM 
--ARMADO DEL CONTENIDO DE LA ENCUESTA
	sge_encuesta_atributo sea --ENCUESTA
	INNER JOIN sge_encuesta_definicion sed on (sea.encuesta = sed.encuesta) --DEFINICIÓN DE ENCUESTA
	INNER JOIN sge_bloque sb on (sed.bloque = sb.bloque)--BLOQUES 
	INNER JOIN sge_pregunta sp on (sed.pregunta = sp.pregunta AND sea.unidad_gestion = sp.unidad_gestion) --PREGUNTAS 
	INNER JOIN sge_componente_pregunta scp on (scp.numero = sp.componente_numero) --COMPONENTES	
--RECUPERACION DE RESPUESTAS RECIBIDAS A LA ENCUESTA
	INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.encuesta = sea.encuesta) --ENCUESTA EN FORMULARIO HABILITADO
	INNER JOIN sge_formulario_habilitado sfh ON (sfhd.formulario_habilitado = sfh.formulario_habilitado) --FORMULARIO HABILITADO
	LEFT JOIN sge_respondido_detalle srd ON (srd.encuesta_definicion = sed.encuesta_definicion) -- DETALLE DE PREGUNTA EN RESPUESTA REGISTRADA
	LEFT JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta 
						AND sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle) --DETALLE DE ENCUESTA EN RESPUESTA REGISTRADA
	LEFT JOIN sge_respondido_formulario srf ON (srf.respondido_formulario = sre.respondido_formulario) --DETALLE DEL FORMULARIO EN RESPUESTA REGISTRADA
	LEFT JOIN mug_localidades mlelegidas ON ( srd.respuesta_valor = mlelegidas.localidad::character varying) --SI ES DE LOCALIDAD EL ID ESTÁ EN EL VALOR DE LA RESPUESTA
	LEFT JOIN sge_elemento se ON (sfhd.elemento = se.elemento)

where sea.encuesta = enc AND sfh.habilitacion = hab AND sfh.formulario_habilitado_externo = form_hab AND se.elemento_externo = elem
			AND scp.componente = 'localidad' AND (sp.tabla_asociada is null OR sp.tabla_asociada = '')
group by sea.encuesta, sb.bloque, sed.encuesta_definicion, sp.pregunta, sed.obligatoria, sed.orden, scp.componente, scp.tipo

UNION

--3) PREGUNTAS LIBRES
SELECT DISTINCT
    sea.encuesta,
    sea.nombre,
    sea.descripcion,
    
    sea.texto_preliminar,
    sea.implementada,
    sea.estado,
    sea.unidad_gestion,

    sb.bloque,
    sb.nombre as bloque_nombre,
    sb.descripcion as bloque_descripcion,
    sb.orden as bloque_orden,

    sed.encuesta_definicion,
    sp.pregunta,
    sp.nombre as pregunta_nombre,
    sp.componente_numero,
    sp.tabla_asociada,
    sp.tabla_asociada_codigo,
    sp.tabla_asociada_descripcion,
    sp.tabla_asociada_orden_campo,
    sp.tabla_asociada_orden_tipo,
    CASE 
        WHEN scp.tipo = 'A' THEN 'S'
        ELSE 'N'
    END as es_libre,
    CASE 
        WHEN sp.componente_numero IN (2,4,5) THEN 'S'
        ELSE 'N'
    END as es_multiple,
    
    sed.obligatoria, 
    sed.orden as pregunta_orden,
    
    scp.componente,

    0::bigint as opciones_respuesta_disponible,
    count (distinct srd.respuesta_valor) as opciones_respuesta_elegidas
FROM 
--ARMADO DEL CONTENIDO DE LA ENCUESTA
	sge_encuesta_atributo sea --ENCUESTA
	INNER JOIN sge_encuesta_definicion sed on (sea.encuesta = sed.encuesta) --DEFINICIÓN DE ENCUESTA
	INNER JOIN sge_bloque sb on (sed.bloque = sb.bloque)--BLOQUES 
	INNER JOIN sge_pregunta sp on (sed.pregunta = sp.pregunta AND sea.unidad_gestion = sp.unidad_gestion) --PREGUNTAS 
	INNER JOIN sge_componente_pregunta scp on (scp.numero = sp.componente_numero) --COMPONENTES
--RECUPERACION DE RESPUESTAS RECIBIDAS A LA ENCUESTA
	INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.encuesta = sea.encuesta) --ENCUESTA EN FORMULARIO HABILITADO
	INNER JOIN sge_formulario_habilitado sfh ON (sfhd.formulario_habilitado = sfh.formulario_habilitado) --FORMULARIO HABILITADO
	LEFT JOIN sge_respondido_detalle srd ON (srd.encuesta_definicion = sed.encuesta_definicion) -- DETALLE DE PREGUNTA EN RESPUESTA REGISTRADA
	LEFT JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta 
						AND sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle) --DETALLE DE ENCUESTA EN RESPUESTA REGISTRADA
	LEFT JOIN sge_respondido_formulario srf ON (srf.respondido_formulario = sre.respondido_formulario) --DETALLE DEL FORMULARIO EN RESPUESTA REGISTRADA
	LEFT JOIN sge_elemento se ON (sfhd.elemento = se.elemento)
	
where sea.encuesta = enc AND sfh.habilitacion = hab AND sfh.formulario_habilitado_externo = form_hab AND se.elemento_externo = elem
			AND (scp.tipo = 'A' OR scp.componente = 'label' )
group by sea.encuesta, sb.bloque, sed.encuesta_definicion, sp.pregunta, sed.obligatoria, sed.orden, scp.componente, scp.tipo;

--sumar las que fueron definidas con tabla asociada 
FOR tabla IN 
	SELECT DISTINCT sp.pregunta, sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion, sp.tabla_asociada_orden_campo
	FROM sge_encuesta_definicion sed 
		INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	WHERE sp.tabla_asociada != '' AND sp.tabla_asociada is not null 
		AND sed.encuesta = enc
  LOOP
	RETURN QUERY EXECUTE 'SELECT 
				    sea.encuesta,
				    sea.nombre,
				    sea.descripcion,
				    
				    sea.texto_preliminar,
				    sea.implementada,
				    sea.estado,
				    sea.unidad_gestion,

				    sb.bloque,
				    sb.nombre as bloque_nombre,
				    sb.descripcion as bloque_descripcion,
				    sb.orden as bloque_orden,

				    sed.encuesta_definicion,
				    sp.pregunta,
				    sp.nombre as pregunta_nombre,
				    sp.componente_numero,
				    sp.tabla_asociada,
				    sp.tabla_asociada_codigo,
				    sp.tabla_asociada_descripcion,
				    sp.tabla_asociada_orden_campo,
				    sp.tabla_asociada_orden_tipo,
				    CASE 
					WHEN scp.tipo = ''A'' THEN ''S''
					ELSE ''N''
				    END as es_libre,
				    CASE 
					WHEN sp.componente_numero IN (2,4,5) THEN ''S''
					ELSE ''N''
				    END as es_multiple,
				    
				    sed.obligatoria, 
				    sed.orden as pregunta_orden,
				    
				    scp.componente,

				    (select count( distinct ' || tabla.tabla_asociada_codigo || ') FROM ' || tabla.tabla_asociada || ') as opciones_respuesta_disponible,
				    count(distinct srd.respuesta_codigo) as opciones_respuesta_elegidas
				FROM 
				--ARMADO DEL CONTENIDO DE LA ENCUESTA
					sge_encuesta_atributo sea --ENCUESTA
					INNER JOIN sge_encuesta_definicion sed on (sea.encuesta = sed.encuesta) --DEFINICIÓN DE ENCUESTA
					INNER JOIN sge_bloque sb on (sed.bloque = sb.bloque)--BLOQUES 
					INNER JOIN sge_pregunta sp on (sed.pregunta = sp.pregunta AND sp.pregunta = ' || tabla.pregunta || ' AND sea.unidad_gestion = sp.unidad_gestion) --PREGUNTAS 
					INNER JOIN sge_componente_pregunta scp on (scp.numero = sp.componente_numero) --COMPONENTES
				--RECUPERACION DE RESPUESTAS RECIBIDAS A LA ENCUESTA
					INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.encuesta = sea.encuesta) --ENCUESTA EN FORMULARIO HABILITADO
					INNER JOIN sge_formulario_habilitado sfh ON (sfhd.formulario_habilitado = sfh.formulario_habilitado) --FORMULARIO HABILITADO
					LEFT JOIN sge_respondido_detalle srd ON (srd.encuesta_definicion = sed.encuesta_definicion) -- DETALLE DE PREGUNTA EN RESPUESTA REGISTRADA
					LEFT JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta 
										AND sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle) --DETALLE DE ENCUESTA EN RESPUESTA REGISTRADA
					LEFT JOIN sge_respondido_formulario srf ON (srf.respondido_formulario = sre.respondido_formulario) --DETALLE DEL FORMULARIO EN RESPUESTA REGISTRADA

					LEFT JOIN ' || tabla.tabla_asociada || ' taelegida ON (srd.respuesta_codigo::character varying = taelegida.' || tabla.tabla_asociada_codigo || '::character varying) --SI ES TABLA ASOCIADA DEBE COINCIDIR CON ALGUN REGISTRO EN DICHA TABLA
					LEFT JOIN sge_elemento se ON (sfhd.elemento = se.elemento)
					
				where sea.encuesta = ' || enc || ' 
							AND sfh.habilitacion = ' || hab || ' 
							AND sfh.formulario_habilitado_externo = ''' || form_hab || ''' 
							AND se.elemento_externo = ''' || elem || ''' 
							AND (sp.tabla_asociada = ''' || tabla.tabla_asociada || ''') AND (scp.tipo = ''C'')
				group by sea.encuesta, sb.bloque, sed.encuesta_definicion, sp.pregunta, sed.obligatoria, sed.orden, scp.componente, scp.tipo';

END LOOP;

RETURN;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;