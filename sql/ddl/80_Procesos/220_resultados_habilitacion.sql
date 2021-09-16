CREATE OR REPLACE FUNCTION kolla.resultados_habilitacion(hab integer, form integer DEFAULT NULL::integer, grp integer DEFAULT NULL::integer, conc integer DEFAULT NULL::integer, elem integer DEFAULT NULL::integer, enc integer DEFAULT NULL::integer, enc_def integer DEFAULT NULL::integer, term character DEFAULT 'T'::bpchar, desde date DEFAULT NULL::date, hasta date DEFAULT NULL::date, enc_def_filtro integer DEFAULT NULL::integer, rta_codigo integer DEFAULT NULL::integer)
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
IF conc is not null THEN cond_conc := ' sconc.concepto = ' 	|| conc;		ELSE cond_conc := ' true '; END IF;
IF elem is not null THEN cond_elem := ' selem.elemento = ' 	|| elem;		ELSE cond_elem := ' true '; END IF;
IF enc is not null 	THEN cond_enc := ' sea.encuesta = ' 	|| enc;			ELSE cond_enc := ' true '; END IF;
IF enc_def is not null THEN cond_preg := ' sed.encuesta_definicion = '		|| enc_def;		ELSE cond_preg := ' true '; END IF;
IF term <> 'T' THEN cond_term := ' srf.terminado = ' || quote_literal(term); ELSE cond_term := ' true '; END IF;
IF desde is null THEN desde := date '2000-01-01'; END IF;
IF hasta is null THEN hasta := now(); END IF;
IF (enc_def_filtro is not null and rta_codigo is not null)
    THEN
        cond_filtro := 'srf.respondido_formulario in (  SELECT respondido_formulario
                                                        FROM obtener_respondidos
                                                        WHERE habilitacion = ' || hab ||
                                                      ' AND encuesta_definicion = ' || enc_def_filtro ||
                                                      ' AND respuesta_codigo = ' || rta_codigo || ')';
    ELSE
        cond_filtro := 'true';
end if;

RETURN QUERY
SELECT
	sfh.habilitacion, --1
	sfh.formulario_habilitado, --2
	sfh.nombre as formulario_nombre, --3
	srf.respondido_formulario, --4
	srf.ingreso, --5
	srf.fecha, --6
	CASE
	  WHEN srf.terminado IS NULL THEN sru.terminado
	  WHEN sru.terminado IS NULL THEN srf.terminado
	  ELSE srf.terminado
	END AS terminado, --7
	CASE
	  WHEN srf.fecha_terminado IS NULL THEN sru.fecha::date
	  WHEN sru.fecha IS NULL THEN srf.fecha_terminado
	  ELSE srf.fecha_terminado
	END AS fecha_terminado, --8
	sre.respondido_encuesta, --9
	srd.respondido_detalle, --10
	srd.moderada,--11
	CASE WHEN (scp.tipo = 'A') THEN 'S' ELSE 'N' END AS es_libre, --12
	CASE WHEN (scp.numero = 4) OR (scp.numero = 5) THEN 'S' ELSE 'N' END AS es_multiple,--13
	CASE
	  WHEN (scp.componente ILIKE 'text%' OR scp.componente = 'fecha_calculo_anios') THEN NULL
	  WHEN ((scp.componente = 'radio' OR scp.componente = 'combo' OR scp.componente = 'list' OR scp.componente = 'check' OR scp.componente = 'combo_dinamico') AND (sp.tabla_asociada IS NULL OR sp.tabla_asociada = ''))
		THEN srd.respuesta_codigo
	  WHEN (TRIM(srd.respuesta_valor) <> '' AND scp.componente = 'localidad') THEN srd.respuesta_valor::integer
	END AS respuesta_codigo, --14
	CASE
	  WHEN (scp.componente ILIKE 'text%' OR scp.componente = 'fecha_calculo_anios') THEN srd.respuesta_valor
	  WHEN ((scp.componente = 'radio' OR scp.componente = 'combo' OR scp.componente = 'list' OR scp.componente = 'check') AND (sp.tabla_asociada IS NULL OR sp.tabla_asociada = ''))
		THEN sr.valor_tabulado
	  WHEN (scp.componente = 'localidad' OR scp.componente = 'localidad_y_cp') THEN ml.nombre
	END AS respuesta_valor,--15
	sed.encuesta_definicion, --16
	sed.encuesta, --17
	sfhd.orden as orden_encuesta, --18
	sb.orden as orden_bloque, --19
	sb.bloque, --20
	sb.nombre as bloque_nombre,--21
	sed.orden as orden_pregunta, --22
	sp.pregunta, --23
	sp.nombre as pregunta_nombre, --24
	scp.componente, --25
	sp.tabla_asociada, --26
	sfh.concepto, --27
	sea.nombre as encuesta_nombre, --28
	sfhd.elemento, --29
	selem.descripcion as elemento_nombre, --30
	sru.respondido_encuestado, --31
	sru.encuestado, --32 encuestado
	se.usuario, --33 usuario del encuestado
	COALESCE(see.usuario, '--')::character varying(60) AS respondido_por_usuario, --34 usuario que respondio
	sru.ignorado, --35
	sconc.descripcion as concepto_nombre, --36
    sconc.concepto_externo as concepto_externo, --37
    selem.elemento_externo as elemento_externo, --38
    sp.tabla_asociada_codigo as pregunta_tabla_codigo, --39
    sp.tabla_asociada_descripcion as pregunta_tabla_descripcion, --40
    scp.numero, --41
    srp.encuestado as respondido_por_encuestado, --42
    (sfhd.encuesta ||
        '_' ||
        CASE WHEN sfhd.tipo_elemento IS NOT NULL THEN sfhd.tipo_elemento || '_' ELSE '' END ||
        sfhd.orden ||
        '_' ||
        sed.encuesta_definicion ||
        CASE WHEN (scp.componente = 'list' OR scp.componente = 'check') THEN '_' || srd.respuesta_codigo ELSE '' END
    )::character varying AS codigo_columna --43
FROM sge_respondido_formulario srf
  INNER JOIN sge_respondido_encuesta sre ON (srf.respondido_formulario = sre.respondido_formulario)
  INNER JOIN sge_respondido_detalle srd ON (sre.respondido_encuesta = srd.respondido_encuesta)
  LEFT JOIN sge_respuesta sr ON (srd.respuesta_codigo = sr.respuesta)
  INNER JOIN sge_encuesta_definicion sed ON (srd.encuesta_definicion = sed.encuesta_definicion
								  AND CASE WHEN enc_def is not null THEN sed.encuesta_definicion = enc_def ELSE true end)
  INNER JOIN sge_encuesta_atributo sea ON (sed.encuesta = sea.encuesta
									  AND CASE WHEN enc is not null THEN sea.encuesta = enc ELSE true end)
  INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
  INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
  INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado
										  AND CASE WHEN form is not null THEN sfh.formulario_habilitado = form ELSE true end)
  INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
  inner join sge_grupo_habilitado sgh on (sgh.formulario_habilitado = sfhd.formulario_habilitado
  										AND CASE WHEN grp is not null THEN sgh.grupo = grp else true end)
  inner join sge_grupo_definicion sgd on (sgd.grupo = sgh.grupo and case when sgd.externo = 'N'
                                                                     then sgd.unidad_gestion::text = sea.unidad_gestion::text
                                                                     else true end)
  INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
  LEFT JOIN sge_respondido_encuestado sru ON (sru.respondido_formulario = srf.respondido_formulario)
  LEFT JOIN sge_encuestado se ON (se.encuestado = sru.encuestado)
  LEFT OUTER JOIN sge_respondido_por srp ON (srf.respondido_formulario = srp.respondido_formulario)
  LEFT OUTER JOIN sge_encuestado see ON (srp.encuestado = see.encuestado)
  LEFT JOIN mug_localidades ml ON (
	CASE
	WHEN (TRIM(srd.respuesta_valor) <> '' AND (scp.componente = 'localidad' OR scp.componente = 'localidad_y_cp')) THEN srd.respuesta_valor::integer
	ELSE NULL
	END = ml.localidad)
  LEFT JOIN sge_elemento selem ON (selem.elemento = sfhd.elemento
								  AND CASE WHEN elem is not null THEN selem.elemento = elem ELSE true end)
  LEFT JOIN sge_concepto sconc ON (sconc.concepto = sfh.concepto
  									AND CASE WHEN conc is not null THEN sconc.concepto = conc ELSE true end)
WHERE
	sfh.habilitacion = hab AND (sp.tabla_asociada = '' OR sp.tabla_asociada IS NULL)
	AND CASE WHEN grp is not null THEN sgh.grupo = grp ELSE true end
	AND CASE WHEN form is not null THEN sfh.formulario_habilitado = form ELSE true end
	AND CASE WHEN conc is not null THEN sconc.concepto = conc ELSE true end
	AND CASE WHEN elem is not null THEN selem.elemento = elem ELSE true end
	AND CASE WHEN enc is not null THEN sea.encuesta = enc ELSE true end
	AND CASE WHEN enc_def is not null THEN sed.encuesta_definicion = enc_def ELSE true end
	AND CASE WHEN term <> 'T' THEN srf.terminado = term ELSE true end
	AND CASE WHEN srf.fecha is not null THEN (srf.fecha >= desde and srf.fecha <= hasta) ELSE true end
	AND CASE WHEN srf.fecha is null THEN (srf.fecha_terminado >= desde and srf.fecha_terminado <= hasta) ELSE true end
	AND CASE WHEN (enc_def_filtro is not null and rta_codigo is not null)
                THEN srf.respondido_formulario in ( SELECT  respondido_formulario
                                                    FROM    obtener_respondidos
                                                    WHERE   habilitacion = hab
                                                    AND     encuesta_definicion = enc_def_filtro
                                                    AND     respuesta_codigo = rta_codigo)
                ELSE true 
            end
;

FOR tabla IN
	SELECT DISTINCT sp.pregunta, sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion, sp.tabla_asociada_orden_campo, sp.tabla_asociada_orden_tipo
	FROM sge_formulario_habilitado sfh
		INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
		inner join sge_grupo_habilitado sgh on (sgh.formulario_habilitado = sfhd.formulario_habilitado
												AND CASE WHEN grp is not null THEN sgh.grupo = grp ELSE true end)
		INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta
												AND CASE WHEN enc_def is not null THEN sed.encuesta_definicion = enc_def ELSE true end)
		INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta
										AND CASE WHEN enc is not null THEN sed.encuesta = enc ELSE true end)
		LEFT JOIN sge_elemento selem ON (selem.elemento = sfhd.elemento
										AND CASE WHEN elem is not null THEN selem.elemento = elem ELSE true end)
		LEFT JOIN sge_concepto sconc ON (sconc.concepto = sfh.concepto
										AND CASE WHEN conc is not null THEN sconc.concepto = conc ELSE true end)
	WHERE sfh.habilitacion = hab AND sp.tabla_asociada != ''
		AND CASE WHEN form is not null THEN sfh.formulario_habilitado = form ELSE true end

LOOP
	RETURN QUERY EXECUTE 'SELECT
			sfh.habilitacion, --1
			sfh.formulario_habilitado, --2
			sfh.nombre as formulario_nombre, --3
			srf.respondido_formulario, --4
			srf.ingreso, --5
			srf.fecha, --6
			CASE
			  WHEN srf.terminado IS NULL THEN sru.terminado
			  WHEN sru.terminado IS NULL THEN srf.terminado
			  ELSE srf.terminado
			END AS terminado, --7
            CASE
                WHEN srf.fecha_terminado IS NULL THEN sru.fecha::date
                WHEN sru.fecha IS NULL THEN srf.fecha_terminado
                ELSE srf.fecha_terminado
            END AS fecha_terminado, --8
			sre.respondido_encuesta, --9
			srd.respondido_detalle, --10
			srd.moderada, --11
			CASE WHEN (scp.tipo = ''A'') THEN ''S'' ELSE ''N'' END AS es_libre, --12
			CASE WHEN (scp.numero = 4) OR (scp.numero = 5) THEN ''S'' ELSE ''N'' END AS es_multiple,--13
			srd.respuesta_codigo, --14
			ta.' || tabla.tabla_asociada_descripcion || '::character varying as respuesta_valor, --15
			sed.encuesta_definicion, --16
			sed.encuesta, --17
			sfhd.orden as orden_encuesta, --18
			sb.orden as orden_bloque, --19
			sb.bloque, --20
			sb.nombre as bloque_nombre, --21
			sed.orden as orden_pregunta, --22
			sp.pregunta, --23
			sp.nombre as pregunta_nombre, --24
			scp.componente, --25
			sp.tabla_asociada, --26
			sfh.concepto, --27
			sea.nombre as encuesta_nombre, --28
			sfhd.elemento, --29
			selem.descripcion as elemento_nombre, --30
			sru.respondido_encuestado, --31
			sru.encuestado, --32 encuestado
			se.usuario, --33 usuario del encuestado
			COALESCE (see.usuario, ''' || '--' || ''')::character varying(60)  AS respondido_por, --34 usuario que respondio
			sru.ignorado, --35
            sconc.descripcion as concepto_nombre, --36
            sconc.concepto_externo as concepto_externo, --37
            selem.elemento_externo as elemento_externo, --38
            sp.tabla_asociada_codigo as pregunta_tabla_codigo, --39
            sp.tabla_asociada_descripcion as pregunta_tabla_descripcion, --40
            scp.numero, --41
            srp.encuestado as respondido_por_encuestado, --42
            (sfhd.encuesta ||
                ''_'' ||
                CASE WHEN sfhd.tipo_elemento IS NOT NULL THEN sfhd.tipo_elemento || ''_'' ELSE '''' END ||
                sfhd.orden ||
                ''_'' ||
                sed.encuesta_definicion ||
                CASE WHEN (scp.componente = ''list'' OR scp.componente = ''check'') THEN ''_'' || srd.respuesta_codigo ELSE '''' END
            )::character varying AS codigo_columna --43
	FROM sge_respondido_formulario srf
	  INNER JOIN sge_respondido_encuesta sre ON (srf.respondido_formulario = sre.respondido_formulario)
	  INNER JOIN sge_respondido_detalle srd ON (sre.respondido_encuesta = srd.respondido_encuesta)
	  INNER JOIN sge_encuesta_definicion sed ON (srd.encuesta_definicion = sed.encuesta_definicion and ' || cond_preg || ' )
	  INNER JOIN sge_encuesta_atributo sea ON (sed.encuesta = sea.encuesta and ' || cond_enc || ')
	  INNER JOIN ' || tabla.tabla_asociada || ' ta ON (srd.respuesta_codigo = ta.' || tabla.tabla_asociada_codigo || '::integer)
	  INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
	  INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta AND sp.pregunta = ' || tabla.pregunta || ')
	  INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado)
	  INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
		inner join sge_grupo_habilitado sgh on (sgh.formulario_habilitado = sfhd.formulario_habilitado
												and		' || cond_grp || ' )
	  inner join sge_grupo_definicion sgd on (sgd.grupo = sgh.grupo and case when sgd.externo = ''N'' then sgd.unidad_gestion::text = sea.unidad_gestion::text else true end)
	  INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
	  LEFT JOIN sge_respondido_encuestado sru ON (sru.respondido_formulario = srf.respondido_formulario)
	  LEFT JOIN sge_encuestado se ON (se.encuestado = sru.encuestado)
	  LEFT OUTER JOIN sge_respondido_por srp ON (srf.respondido_formulario = srp.respondido_formulario)
	  LEFT OUTER JOIN sge_encuestado see ON (srp.encuestado = see.encuestado)
	  LEFT JOIN sge_elemento selem ON (selem.elemento = sfhd.elemento)
          LEFT JOIN sge_concepto sconc ON (sconc.concepto = sfh.concepto and ' || cond_conc || ')
	WHERE  sfh.habilitacion = ' || hab || '
		and (sp.tabla_asociada = ''' || tabla.tabla_asociada || ''')
		and ' || cond_form || '
		and ' || cond_term || '
		and ' || cond_filtro || '
		and ' || cond_elem || 
	      ' and CASE WHEN srf.fecha is not null THEN (srf.fecha >= ''' || desde || ''' and srf.fecha <= ''' || hasta || ''') ELSE true end
	        and CASE WHEN srf.fecha is null THEN (srf.fecha_terminado >= ''' || desde || ''' and srf.fecha_terminado <= ''' || hasta || ''') ELSE true end
		;' ;
END LOOP;

RETURN;
END;
$function$
;
