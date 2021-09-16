CREATE OR REPLACE FUNCTION ws_encuesta_definicion(ug character varying, enc integer, bl integer, preg integer)
  RETURNS SETOF record AS
$BODY$
DECLARE 
tabla record;
cond_bl character varying;
cond_preg character varying;

BEGIN

IF bl is not null THEN cond_bl := ' sed.bloque = ' || bl; ELSE cond_bl := ' true '; END IF;
IF preg is not null THEN cond_preg := ' sp.pregunta = ' || preg; ELSE cond_preg := ' true '; END IF;

RETURN QUERY 

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

    sp.pregunta,
    sp.nombre as pregunta_nombre,
    sp.componente_numero,
    sp.tabla_asociada,
    sp.tabla_asociada_codigo,
    sp.tabla_asociada_descripcion,
    sp.tabla_asociada_orden_campo,
    sp.tabla_asociada_orden_tipo,
    --sp.unidad_gestion,
    sp.descripcion_resumida,
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

    sr.respuesta,
    sr.valor_tabulado as respuesta_valor,
    spr.orden as respuesta_orden
FROM sge_encuesta_atributo sea 
	INNER JOIN sge_encuesta_definicion sed on (sea.encuesta = sed.encuesta)
	INNER JOIN sge_bloque sb on (sed.bloque = sb.bloque 
				AND CASE WHEN bl is not null THEN sed.bloque = bl ELSE true END)
	INNER JOIN sge_pregunta sp on (sed.pregunta = sp.pregunta AND sea.unidad_gestion = sp.unidad_gestion
				AND CASE WHEN preg is not null THEN sp.pregunta = preg ELSE true END)
	INNER JOIN sge_componente_pregunta scp on (scp.numero = sp.componente_numero)
	LEFT JOIN sge_pregunta_respuesta spr ON (
		CASE
		WHEN (scp.tipo = 'C') THEN sp.pregunta
		ELSE NULL
		END = spr.pregunta)
	LEFT JOIN sge_respuesta sr ON (spr.respuesta = sr.respuesta)
WHERE sea.encuesta = enc and sea.unidad_gestion = ug and ((sp.tabla_asociada is null) OR (sp.tabla_asociada = ''));

--sumar las que fueron definidas con tabla asociada 
FOR tabla IN 
	SELECT DISTINCT sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion, sp.tabla_asociada_orden_campo
	FROM sge_encuesta_definicion sed 
		INNER JOIN sge_bloque sb on (sed.bloque = sb.bloque
			AND CASE WHEN bl is not null THEN sed.bloque = bl ELSE true END)
		INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	WHERE sp.tabla_asociada != '' AND sp.tabla_asociada is not null 
		AND sed.encuesta = enc AND sp.unidad_gestion = ug
  LOOP
	RETURN QUERY EXECUTE '
		SELECT DISTINCT
		    sea.encuesta, --1
		    sea.nombre, --2
		    sea.descripcion, --3
		    
		    sea.texto_preliminar, --4
		    sea.implementada, --5
		    sea.estado, --6
		    sea.unidad_gestion, --7

		    sb.bloque, --8
		    sb.nombre as bloque_nombre, --9
		    sb.descripcion as bloque_descripcion, --10
		    sb.orden as bloque_orden, --11

		    sp.pregunta, --12
		    sp.nombre as pregunta_nombre, --13
		    sp.componente_numero, --14
		    sp.tabla_asociada, --15
		    sp.tabla_asociada_codigo, --16
		    sp.tabla_asociada_descripcion, --17
		    sp.tabla_asociada_orden_campo, --18
		    sp.tabla_asociada_orden_tipo, --19
		    sp.descripcion_resumida, --20
		    CASE 
                WHEN scp.tipo = ''A'' THEN ''S''
                ELSE ''N''
		    END as es_libre, --21
		    CASE 
                WHEN sp.componente_numero IN (2,4,5) THEN ''S''
                ELSE ''N''
		    END as es_multiple, --22
		    
		    sed.obligatoria, --23
		    sed.orden as pregunta_orden, --24
		    
		    scp.componente, --25

		    ta.' || tabla.tabla_asociada_codigo || ' as respuesta, --26
		    ta.' || tabla.tabla_asociada_descripcion || '::character varying as respuesta_valor, --27
		    ta.' || tabla.tabla_asociada_codigo || '::smallint as respuesta_orden --28
		FROM sge_encuesta_atributo sea 
			INNER JOIN sge_encuesta_definicion sed on (sea.encuesta = sed.encuesta)
			INNER JOIN sge_bloque sb on (sed.bloque = sb.bloque AND ' || cond_bl || ')
			INNER JOIN sge_pregunta sp on (sed.pregunta = sp.pregunta  
							AND sea.unidad_gestion = sp.unidad_gestion
							AND ' || cond_preg || ') 
			INNER JOIN sge_componente_pregunta scp on (scp.numero = sp.componente_numero)
			, ' || tabla.tabla_asociada || ' ta 
		WHERE sea.encuesta = ' || enc || '  
            AND sea.unidad_gestion = ''' || ug || ''' 
			AND scp.numero != 7 
			AND (sp.tabla_asociada = ''' || tabla.tabla_asociada || ''')';
END LOOP;

RETURN;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100
  ROWS 1000;
ALTER FUNCTION ws_encuesta_definicion(ug character varying, enc integer, bl integer, preg integer)
  OWNER TO postgres;