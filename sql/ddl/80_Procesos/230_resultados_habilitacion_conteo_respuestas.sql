CREATE OR REPLACE FUNCTION resultados_habilitacion_conteo_respuestas(hab integer, form integer DEFAULT NULL::integer, grp integer DEFAULT NULL::integer, conc integer DEFAULT NULL::integer, elem integer DEFAULT NULL::integer, enc integer DEFAULT NULL::integer, enc_def integer DEFAULT NULL::integer, term character DEFAULT 'T'::bpchar, desde date DEFAULT NULL::date, hasta date DEFAULT NULL::date, enc_def_filtro integer DEFAULT NULL::integer, rta_codigo integer DEFAULT NULL::integer)
 RETURNS SETOF record
 LANGUAGE plpgsql
AS $function$

DECLARE
tabla record;
cond_grp character varying;
cond_form character varying;
cond_conc character varying;
cond_elem character varying;
cond_enc character varying;
cond_preg character varying;
cond_term character varying;
cond_filtro character varying;


BEGIN

IF grp is not null 	THEN cond_grp := ' sgh.grupo = ' 		|| grp;			ELSE cond_grp := ' true '; END IF;
IF form is not null THEN cond_form := ' sfh.formulario_habilitado = ' || form; ELSE cond_form := ' true '; END IF;
IF conc is not null THEN cond_conc := ' sc.concepto = ' 	|| conc;		ELSE cond_conc := ' true '; END IF;
IF elem is not null THEN cond_elem := ' se.elemento = ' 	|| elem;		ELSE cond_elem := ' true '; END IF;
IF enc is not null 	THEN cond_enc := ' sea.encuesta = ' 	|| enc;			ELSE cond_enc := ' true '; END IF;
IF enc_def is not null THEN cond_preg := ' sed.encuesta_definicion = '		|| enc_def;		ELSE cond_preg := ' true '; END IF;
IF term <> 'T' THEN cond_term := ' srf.terminado = ' ||	quote_literal(term); ELSE cond_term := ' true '; END IF;
IF desde is null THEN desde := date '2000-01-01'; END IF;
IF hasta is null THEN hasta := now(); END IF;

IF (enc_def_filtro is not null and rta_codigo is not null) THEN cond_filtro := 'srf.respondido_formulario in (SELECT respondido_formulario
																							                FROM obtener_respondidos
																							                WHERE habilitacion = ' || hab ||
																						                    ' AND encuesta_definicion = ' || enc_def_filtro ||
																						                    ' AND respuesta_codigo = ' || rta_codigo || ')';
													       ELSE cond_filtro := 'true'; end if;



RETURN QUERY
select
  sp.pregunta AS pregunta, --1
  sp.nombre AS pregunta_nombre, --2
	CASE
	  WHEN (scp.componente ILIKE 'text%' OR scp.componente = 'fecha_calculo_anios') THEN NULL
	  WHEN (scp.componente = 'radio' OR scp.componente = 'combo' OR scp.componente = 'list' OR scp.componente = 'check' OR scp.componente = 'combo_dinamico')
		THEN srd.respuesta_codigo
	  WHEN (TRIM(srd.respuesta_valor) <> '' AND scp.componente = 'localidad') THEN srd.respuesta_valor::integer
	END AS respuesta_codigo, --3
	CASE
	  WHEN (scp.componente = 'radio' OR scp.componente = 'combo' OR scp.componente = 'list' OR scp.componente = 'check') THEN sr.valor_tabulado
	  WHEN (scp.componente = 'localidad' OR scp.componente = 'localidad_y_cp') THEN ml.nombre
	  WHEN (scp.componente ILIKE 'text%' OR scp.componente = 'fecha_calculo_anios') THEN srd.respuesta_valor
	  else srd.respuesta_valor
	END AS respuesta_valor, --4
  count(respuesta_codigo) AS cantidad_elegidas, --5
  sc.concepto_externo AS concepto_externo, --6
  sc.descripcion AS concepto_nombre ,--7
  se.elemento_externo AS elemento_externo, --8
  se.descripcion AS elemento_nombre, --9
  sea.nombre AS encuesta_nombre, --10
  sb.nombre AS bloque_nombre, --11
  sfh.habilitacion AS habilitacion, --12
  sfhd.orden AS orden_encuesta, --13
  sb.orden AS orden_bloque, --14
  sed.orden AS orden_pregunta, --15
  spr.orden::character varying AS orden_respuesta  --16
FROM
	sge_respondido_formulario srf
	  	inner join sge_respondido_encuesta sre on (sre.respondido_formulario = srf.respondido_formulario)
		inner join sge_respondido_detalle srd on (srd.respondido_encuesta = sre.respondido_encuesta)
  		inner join sge_formulario_habilitado sfh on (sfh.formulario_habilitado = srf.formulario_habilitado and sfh.habilitacion = hab)
  		inner join sge_grupo_habilitado sgh on (sgh.formulario_habilitado = sfh.formulario_habilitado)
		INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado and
															sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
		inner join sge_encuesta_definicion sed on (srd.encuesta_definicion = sed.encuesta_definicion)
		inner join sge_encuesta_atributo sea on (sea.encuesta = sed.encuesta and sfhd.encuesta = sed.encuesta)
	 	inner join sge_bloque sb on (sb.bloque = sed.bloque)
	 	inner join sge_pregunta sp on (sp.pregunta = sed.pregunta AND (sp.tabla_asociada = '' OR sp.tabla_asociada IS NULL))
	 	INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
 		LEFT JOIN sge_pregunta_respuesta spr ON (
	 		case when scp.tipo = 'C' then sp.pregunta
	 		else null
 			end = spr.pregunta and spr.respuesta = srd.respuesta_codigo)
		left JOIN sge_respuesta sr ON  (
			case when scp.tipo = 'C' then spr.respuesta
			else null
			end = sr.respuesta)
		LEFT JOIN mug_localidades ml ON (
		CASE
			WHEN (TRIM(srd.respuesta_valor) <> '' AND (scp.componente = 'localidad' OR scp.componente = 'localidad_y_cp')) THEN srd.respuesta_valor::integer
			ELSE NULL
		END = ml.localidad)
		LEFT JOIN sge_concepto sc ON (sc.concepto = sfh.concepto)
		LEFT JOIN sge_elemento se ON (se.elemento = sfhd.elemento)
WHERE TRUE
	AND CASE WHEN grp is not null THEN sgh.grupo = grp ELSE true end
	AND CASE WHEN form is not null THEN sfh.formulario_habilitado = form ELSE true end
	AND CASE WHEN conc is not null THEN sc.concepto = conc ELSE true end
	AND CASE WHEN elem is not null THEN se.elemento = elem ELSE true end
	AND CASE WHEN enc is not null THEN sea.encuesta = enc ELSE true end
	AND CASE WHEN enc_def is not null THEN sed.encuesta_definicion = enc_def ELSE true end
	AND CASE WHEN term <> 'T' THEN srf.terminado = term ELSE true end
	AND CASE WHEN srf.fecha is not null THEN (srf.fecha >= desde and srf.fecha <= hasta) ELSE true end
	AND CASE WHEN srf.fecha_terminado is not null THEN (srf.fecha_terminado >= desde and srf.fecha_terminado <= hasta) ELSE true end
	AND CASE WHEN (enc_def_filtro is not null and rta_codigo is not null) THEN srf.respondido_formulario in (SELECT respondido_formulario
																							                FROM obtener_respondidos
																							                WHERE habilitacion = hab
																						                    AND encuesta_definicion = enc_def_filtro
																						                     AND respuesta_codigo = rta_codigo)
													                     ELSE true end
GROUP BY 1,2,3,4,6,7,8,9,10,11,12,13,14,15,16;

FOR tabla IN
	SELECT DISTINCT sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion, sp.tabla_asociada_orden_campo
	FROM sge_formulario_habilitado sfh
		INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
		INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta)
		INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	WHERE sfh.formulario_habilitado = form AND sp.tabla_asociada != ''
LOOP
	RETURN QUERY EXECUTE '
			SELECT
				  sp.pregunta, --1
				  sp.nombre AS pregunta_nombre, --2
				  srd.respuesta_codigo, --3
				  ta.' || tabla.tabla_asociada_descripcion || '::character varying as respuesta_valor, --4
				  count(respuesta_codigo) AS cantidad_elegidas, --5
				  sc.concepto_externo, --6
				  sc.descripcion AS concepto_nombre ,--7
				  se.elemento_externo, --8
				  se.descripcion AS elemento_nombre, --9
				  sea.nombre AS elemento_nombre, --10
				  sb.nombre AS bloque_nombre, --11
				  sfh.habilitacion, --12
				  sfhd.orden AS orden_encuesta, --13
				  sb.orden AS orden_bloque, --14
				  sed.orden AS orden_pregunta, --15
				  CASE
					WHEN (sp.tabla_asociada_orden_campo = ' || '''codigo''' || ')
					THEN ta. ' || tabla.tabla_asociada_codigo || ' ::character varying
					ELSE ta. ' || tabla.tabla_asociada_descripcion || ' ::character varying
					END AS orden_respuesta  --16
			FROM
				sge_respondido_formulario srf
				  	inner join sge_respondido_encuesta sre on (sre.respondido_formulario = srf.respondido_formulario)
					inner join sge_respondido_detalle srd on (srd.respondido_encuesta = sre.respondido_encuesta)
			  		inner join sge_formulario_habilitado sfh on (sfh.formulario_habilitado = srf.formulario_habilitado and sfh.habilitacion = ' || hab || ' )
			  		inner join sge_grupo_habilitado sgh on (sgh.formulario_habilitado = sfh.formulario_habilitado)
					INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado and
																		sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
					inner join sge_encuesta_definicion sed on (srd.encuesta_definicion = sed.encuesta_definicion)
					inner join sge_encuesta_atributo sea on (sea.encuesta = sed.encuesta and sfhd.encuesta = sed.encuesta)
				 	inner join sge_bloque sb on (sb.bloque = sed.bloque)
				 	inner join sge_pregunta sp on (sp.pregunta = sed.pregunta AND sp.tabla_asociada = ''' || tabla.tabla_asociada || ''')
					INNER JOIN ' || tabla.tabla_asociada || ' ta ON (srd.respuesta_codigo = ta.' || tabla.tabla_asociada_codigo || '::integer)
					LEFT JOIN sge_concepto sc ON (sc.concepto = sfh.concepto)
					LEFT JOIN sge_elemento se ON (se.elemento = sfhd.elemento)

			WHERE TRUE AND
				' || cond_grp ||
				' and ' || cond_form ||
				' and ' || cond_conc ||
				' and ' || cond_elem ||
				' and ' || cond_enc ||
				' and ' || cond_preg ||
				' and ' || cond_term ||
				' and CASE WHEN srf.fecha is not null THEN (srf.fecha >= ''' || desde || ''' and srf.fecha <= ''' || hasta || ''') ELSE true end
				  and CASE WHEN srf.fecha_terminado is not null THEN (srf.fecha_terminado >= ''' || desde || ''' and srf.fecha_terminado <= ''' || hasta || ''') ELSE true end
				  and ' || cond_filtro ||
				' GROUP BY 1,2,3,4,6,7,8,9,10,11,12,13,14,15,16 ;';

END LOOP;


RETURN;
END;
$function$
;
