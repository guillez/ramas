CREATE OR REPLACE FUNCTION respuestas_formulario_externo(hab integer, form character varying, cod_ext character varying, sist integer)
 RETURNS SETOF record AS
$BODY$
DECLARE
tabla record;

BEGIN

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
	CASE
	  WHEN (scp.componente ILIKE 'text%' OR scp.componente = 'fecha_calculo_anios') THEN NULL
	  WHEN ((scp.componente = 'radio' OR scp.componente = 'combo' OR scp.componente = 'list' OR scp.componente = 'check' OR scp.componente = 'combo_dinamico') AND (sp.tabla_asociada IS NULL OR sp.tabla_asociada = ''))
		THEN srd.respuesta_codigo
	  WHEN (TRIM(srd.respuesta_valor) <> '' AND scp.componente = 'localidad') THEN srd.respuesta_valor::integer
	END AS respuesta_codigo, --12
	CASE
	  WHEN (scp.componente ILIKE 'text%' OR scp.componente = 'fecha_calculo_anios') THEN srd.respuesta_valor
	  WHEN ((scp.componente = 'radio' OR scp.componente = 'combo' OR scp.componente = 'list' OR scp.componente = 'check') AND (sp.tabla_asociada IS NULL OR sp.tabla_asociada = ''))
		THEN sr.valor_tabulado
	  WHEN (scp.componente = 'localidad' OR scp.componente = 'localidad_y_cp') THEN ml.nombre
	END AS respuesta_valor,--13
	sed.encuesta_definicion, --14
	sed.encuesta, --15
	sfhd.orden as orden_encuesta, --16
	sb.orden as orden_bloque, --17
	sb.bloque, --18
	sb.nombre as bloque_nombre,--19
	sed.orden as orden_pregunta, --20
	sp.pregunta, --21
	sp.nombre as pregunta_nombre, --22
	scp.componente, --23
	sp.tabla_asociada, --24
	sfh.concepto, --25
	sea.nombre as encuesta_nombre, --26
	sfhd.elemento, --27
	selem.descripcion as elemento_nombre, --28
	sru.respondido_encuestado, --29
	sru.encuestado, --30 encuestado
	se.usuario, --31 usuario del encuestado
	COALESCE(see.usuario, '--')::character varying(60) AS respondido_por_usuario, --32 usuario que respondio
	sru.ignorado, --33
	sconc.descripcion as concepto_nombre, --34
    sconc.concepto_externo as concepto_externo, --35
    selem.elemento_externo as elemento_externo, --36
    sp.tabla_asociada_codigo as pregunta_tabla_codigo, --37
    sp.tabla_asociada_descripcion as pregunta_tabla_descripcion, --38
    scp.numero, --39
    srp.encuestado as respondido_por_encuestado, --40
    (sfhd.encuesta ||
        '_' ||
        CASE WHEN sfhd.tipo_elemento IS NOT NULL THEN sfhd.tipo_elemento || '_' ELSE '' END ||
        sfhd.orden ||
        '_' ||
        sed.encuesta_definicion ||
        CASE WHEN (scp.componente = 'list' OR scp.componente = 'check') THEN '_' || srd.respuesta_codigo ELSE '' END
    )::character varying AS codigo_columna --41
FROM sge_respondido_formulario srf
  INNER JOIN sge_respondido_encuesta sre ON (srf.respondido_formulario = sre.respondido_formulario)
  INNER JOIN sge_respondido_detalle srd ON (sre.respondido_encuesta = srd.respondido_encuesta)
  LEFT JOIN sge_respuesta sr ON (srd.respuesta_codigo = sr.respuesta)
  INNER JOIN sge_encuesta_definicion sed ON (srd.encuesta_definicion = sed.encuesta_definicion)
  INNER JOIN sge_encuesta_atributo sea ON (sed.encuesta = sea.encuesta)
  INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
  INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
  INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado)
  INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
  INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
  INNER JOIN sge_respondido_encuestado sru ON (sru.respondido_formulario = srf.respondido_formulario
  										and sru.codigo_externo = cod_ext
  										and sru.sistema = sist)
  LEFT JOIN sge_encuestado se ON (se.encuestado = sru.encuestado)
  LEFT OUTER JOIN sge_respondido_por srp ON (srf.respondido_formulario = srp.respondido_formulario)
  LEFT OUTER JOIN sge_encuestado see ON (srp.encuestado = see.encuestado)
  LEFT JOIN mug_localidades ml ON (
	CASE
	WHEN (TRIM(srd.respuesta_valor) <> '' AND (scp.componente = 'localidad' OR scp.componente = 'localidad_y_cp')) THEN srd.respuesta_valor::integer
	ELSE NULL
	END = ml.localidad)
  LEFT JOIN sge_elemento selem ON (selem.elemento = sfhd.elemento)
  LEFT JOIN sge_concepto sconc ON (sconc.concepto = sfh.concepto)
where sfh.habilitacion = hab and sfh.formulario_habilitado_externo = form
	AND (sp.tabla_asociada = '' OR sp.tabla_asociada IS NULL) ;

FOR tabla IN
	SELECT DISTINCT sp.pregunta, sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion
	FROM sge_formulario_habilitado sfh
		INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
		INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta)
		INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	WHERE sfh.formulario_habilitado_externo = form and sfh.habilitacion = hab AND sp.tabla_asociada != ''
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
			srd.respuesta_codigo, --12
			ta.' || tabla.tabla_asociada_descripcion || '::character varying as respuesta_valor, --13
			sed.encuesta_definicion, --14
			sed.encuesta, --15
			sfhd.orden as orden_encuesta, --16
			sb.orden as orden_bloque, --17
			sb.bloque, --18
			sb.nombre as bloque_nombre, --19
			sed.orden as orden_pregunta, --20
			sp.pregunta, --21
			sp.nombre as pregunta_nombre, --22
			scp.componente, --23
			sp.tabla_asociada, --24
			sfh.concepto, --25
			sea.nombre as encuesta_nombre, --26
			sfhd.elemento, --27
			selem.descripcion as elemento_nombre, --28
			sru.respondido_encuestado, --29
			sru.encuestado, --30 encuestado
			se.usuario, --31 usuario del encuestado
			COALESCE (see.usuario, ''' || '--' || ''')::character varying(60)  AS respondido_por, --32 usuario que respondio
			sru.ignorado, --33
            sconc.descripcion as concepto_nombre, --34
            sconc.concepto_externo as concepto_externo, --35
            selem.elemento_externo as elemento_externo, --36
            sp.tabla_asociada_codigo as pregunta_tabla_codigo, --37
            sp.tabla_asociada_descripcion as pregunta_tabla_descripcion, --38
            scp.numero, --39
            srp.encuestado as respondido_por_encuestado, --40
            (sfhd.encuesta ||
                ''_'' ||
                CASE WHEN sfhd.tipo_elemento IS NOT NULL THEN sfhd.tipo_elemento || ''_'' ELSE '''' END ||
                sfhd.orden ||
                ''_'' ||
                sed.encuesta_definicion ||
                CASE WHEN (scp.componente = ''list'' OR scp.componente = ''check'') THEN ''_'' || srd.respuesta_codigo ELSE '''' END
            )::character varying AS codigo_columna --41

	FROM sge_respondido_formulario srf
	  INNER JOIN sge_respondido_encuesta sre ON (srf.respondido_formulario = sre.respondido_formulario)
	  INNER JOIN sge_respondido_detalle srd ON (sre.respondido_encuesta = srd.respondido_encuesta)
	  INNER JOIN sge_encuesta_definicion sed ON (srd.encuesta_definicion = sed.encuesta_definicion)
	  INNER JOIN sge_encuesta_atributo sea ON (sed.encuesta = sea.encuesta)
	  INNER JOIN ' || tabla.tabla_asociada || ' ta ON (srd.respuesta_codigo = ta.' || tabla.tabla_asociada_codigo || '::integer)
	  INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
	  INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta AND sp.pregunta = ' || tabla.pregunta || ')
	  INNER JOIN sge_formulario_habilitado sfh ON (srf.formulario_habilitado = sfh.formulario_habilitado)
	  INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
	  INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
	  INNER JOIN sge_respondido_encuestado sru ON (sru.respondido_formulario = srf.respondido_formulario
													and sru.sistema = ' || sist || '
													and sru.codigo_externo = ''' || cod_ext || ''')
	  LEFT JOIN sge_encuestado se ON (se.encuestado = sru.encuestado)
	  LEFT OUTER JOIN sge_respondido_por srp ON (srf.respondido_formulario = srp.respondido_formulario)
	  LEFT OUTER JOIN sge_encuestado see ON (srp.encuestado = see.encuestado)
	  LEFT JOIN sge_elemento selem ON (selem.elemento = sfhd.elemento)
      LEFT JOIN sge_concepto sconc ON (sconc.concepto = sfh.concepto)
	WHERE sfh.formulario_habilitado_externo = ''' || form || '''
 			and sfh.habilitacion = ' || hab || '
		and (sp.tabla_asociada = ''' || tabla.tabla_asociada || ''')' ;
END LOOP;

RETURN;
END;
$BODY$
LANGUAGE plpgsql VOLATILE
