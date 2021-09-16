CREATE OR REPLACE FUNCTION estimar_cantidad_resultados(hab integer, form integer DEFAULT NULL::integer, grp integer DEFAULT NULL::integer, conc integer DEFAULT NULL::integer, elem integer DEFAULT NULL::integer, enc integer DEFAULT NULL::integer, enc_def integer DEFAULT NULL::integer, term character DEFAULT 'T'::bpchar, desde date DEFAULT NULL::date, hasta date DEFAULT NULL::date, enc_def_preg integer DEFAULT NULL::integer, rta_codigo integer DEFAULT NULL::integer)
 RETURNS SETOF record
 LANGUAGE plpgsql
AS $function$

DECLARE

BEGIN

IF desde is null THEN desde := date '2000-01-01'; END IF;
IF hasta is null THEN hasta := now(); END IF;

RETURN QUERY
SELECT
	count(distinct srf.respondido_formulario) as total_respuestas_recibidas_habilitacion
FROM sge_respondido_formulario srf
     INNER JOIN sge_formulario_habilitado sfh ON sfh.formulario_habilitado = srf.formulario_habilitado
     INNER JOIN sge_grupo_habilitado sgh ON sgh.formulario_habilitado = sfh.formulario_habilitado
     INNER JOIN sge_formulario_habilitado_detalle sfhd ON sfhd.formulario_habilitado = sfh.formulario_habilitado
     INNER JOIN sge_habilitacion sh ON sh.habilitacion = sfh.habilitacion
     INNER JOIN sge_grupo_definicion sgd ON (sgd.grupo = sgh.grupo and case when sgd.externo = 'N'
     																		then sgd.unidad_gestion::text = sh.unidad_gestion::text
     																		else true end)
WHERE
	sfh.habilitacion = hab
	AND CASE WHEN form is not null THEN sfh.formulario_habilitado = form ELSE true end --si se filtra por formulario_habilitado
	AND CASE WHEN conc is not null THEN sfh.concepto = conc ELSE true end --si se filtra por concepto
	AND CASE WHEN grp is not null THEN sgh.grupo = grp ELSE true end --si se filtra por grupo
	AND CASE WHEN elem is not null THEN sfhd.elemento = elem ELSE true end  --si se filtra por elemento
	AND CASE WHEN enc is not null THEN sfhd.encuesta = enc ELSE true end --si se filtra por encuesta
	AND CASE WHEN enc_def is not null then
					srf.respondido_formulario in (select sre.respondido_formulario
									from sge_respondido_encuesta sre
													 inner join sge_respondido_detalle srd on srd.respondido_encuesta = sre.respondido_encuesta and srd.encuesta_definicion = enc_def
									where sre.respondido_formulario = srf.respondido_formulario and sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
	else true end
	AND CASE WHEN term <> 'T' THEN srf.terminado = term ELSE true end --si se filtra por estado de la respuesta terminada o no
	AND CASE WHEN srf.fecha is not null THEN (srf.fecha >= desde and srf.fecha <= hasta) ELSE true end	-- si se filtra por fecha desde
	AND CASE WHEN srf.fecha is null THEN (srf.fecha_terminado >= desde and srf.fecha_terminado <= hasta) ELSE true end --si se filtra por fecha hasta
	and case when (enc_def_preg is not null and rta_codigo is not null)
		then srf.respondido_formulario in (SELECT respondido_formulario
						                FROM obtener_respondidos
						                WHERE habilitacion = hab AND encuesta_definicion = enc_def_preg AND respuesta_codigo = rta_codigo)
		else true end
;

RETURN;
END;
$function$
;
