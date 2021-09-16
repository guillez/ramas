CREATE OR REPLACE FUNCTION preguntas_con_respuestas_resultados_encuestado (
											hab integer,
											form integer DEFAULT NULL::integer,
											grp integer DEFAULT NULL::integer,
											conc integer DEFAULT NULL::integer,
											elem integer DEFAULT NULL::integer,
											enc integer DEFAULT NULL::integer,
											enc_def integer DEFAULT NULL::integer,
											filtro_preguntas boolean default false,
											filtro_respuestas boolean default false)
 RETURNS SETOF record
 LANGUAGE plpgsql
AS $function$
DECLARE

preguntas record;
tabla record;

cant_opciones integer;
limite_opciones integer;

cond_grp character varying;
cond_form character varying;
cond_conc character varying;
cond_elem character varying;
cond_enc character varying;
cond_preg character varying;


BEGIN

--IF grp is not null 	THEN cond_grp := ' sgh.grupo = ' 		|| grp;			ELSE cond_grp := ' true '; END IF;
--AND CASE WHEN grp is not null THEN sgh.grupo = grp else true end)
IF form is not null     THEN cond_form := ' sfh.formulario_habilitado = ' || form; ELSE cond_form := ' true '; END IF;
IF conc is not null     THEN cond_conc := ' sfh.concepto = ' 	|| conc;		ELSE cond_conc := ' true '; END IF;
IF elem is not null     THEN cond_elem := ' sfhd.elemento = ' 	|| elem;		ELSE cond_elem := ' true '; END IF;
IF enc is not null      THEN cond_enc := ' sfhd.encuesta = ' 	|| enc;			ELSE cond_enc := ' true '; END IF;
IF enc_def is not null  THEN cond_preg := ' sed.encuesta_definicion = '		|| enc_def;		ELSE cond_preg := ' true '; END IF;

SELECT valor
  FROM sge_parametro_configuracion
  WHERE seccion = 'REPORTES' AND parametro = 'limite_opciones_respuesta_multiple' INTO limite_opciones ;

RETURN QUERY

--PRIMERO OBTENEMOS TODAS LAS QUE NO TIENEN OPCIONES DE RESPUESTA
SELECT DISTINCT
	(sfhd.encuesta || '_' ||
		CASE when sfhd.tipo_elemento IS NOT NULL THEN sfhd.tipo_elemento || '_' ELSE '' END ||
		sfhd.orden || '_' || sed.encuesta_definicion)::character varying
	AS codigo_columna,          --1
    sfh.habilitacion,               --2
	sfhd.encuesta,                  --3
	sfhd.orden as encuesta_orden,   --4
	sed.encuesta_definicion,        --5
	sed.bloque,                     --6
	sb.orden as bloque_orden,       --7
	sed.pregunta,                   --8
	sed.orden as pregunta_orden,    --9
	sp.nombre::text	as pregunta_nombre,         --10
	scp.numero as componente_numero,--11
	scp.componente as componente,   --12
	'no'::text as opciones_multiples,      --13
	-1 as respuesta_codigo, --14
	''::character varying as valor_tabulado, --15
	-1 as respuesta_orden,   --16
    (CASE WHEN sfhd.elemento IS NOT NULL THEN 1 ELSE 0 END)::SMALLINT AS hay_elemento,  --17
--    sfh.concepto as concepto, --18
--    sfhd.elemento as elemento, --19
     '' as tabla_asociada --20
FROM sge_formulario_habilitado sfh
	INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado
														AND CASE WHEN form is not null THEN sfh.formulario_habilitado = form ELSE true end
														AND CASE WHEN elem is not null THEN sfhd.elemento = elem ELSE true end
														AND CASE WHEN enc is not null THEN sfhd.encuesta = enc ELSE true end)
	INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta
									AND CASE WHEN enc_def is not null THEN sed.encuesta_definicion = enc_def ELSE true end
								--PARA INCLUIR SOLO LAS PREGUNTAS QUE FUERON RESPONDIDAS
										AND
											CASE -- SI SE PIDIÓ EL FILTRO SE AGREGA
											WHEN filtro_preguntas THEN
												EXISTS (SELECT  srd.respondido_detalle 
                                                        FROM    sge_respondido_detalle srd
                                                                    INNER JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta)
                                                                    INNER JOIN sge_pregunta sp2 ON (sp2.pregunta = sed.pregunta)
                                                                    INNER JOIN sge_componente_pregunta scp2 ON (scp2.numero = sp2.componente_numero)
                                                        WHERE   srd.encuesta_definicion = sed.encuesta_definicion
                                                        AND     sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle
                                                        AND ((scp2.tipo = 'C' AND srd.respuesta_codigo IS NOT NULL)
                                                            OR (scp2.tipo = 'A' AND srd.respuesta_valor IS NOT NULL AND TRIM(srd.respuesta_valor) != ''))
											            )
											ELSE --SI NO SE PIDIÓ NO SE FILTRA
												true
											END
										)
	INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
	INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
WHERE
	sfh.habilitacion = hab
	and ((sp.tabla_asociada is null) OR (sp.tabla_asociada = ''))
	and (componente != 'label') and (componente != 'etiqueta_titulo') and (componente != 'etiqueta_texto_enriquecido')
	and (componente != 'check') and (componente != 'list')
	AND CASE WHEN conc is not null THEN sfh.concepto = conc ELSE true end

UNION

SELECT DISTINCT
	(sfhd.encuesta || '_' ||
		CASE when sfhd.tipo_elemento IS NOT NULL THEN sfhd.tipo_elemento || '_' ELSE '' END ||
		sfhd.orden || '_' || sed.encuesta_definicion || '_' || spr.respuesta )::character varying
		AS codigo_columna,          --1
    sfh.habilitacion,               --2
	sfhd.encuesta,                  --3
	sfhd.orden as encuesta_orden,   --4
	sed.encuesta_definicion,        --5
	sed.bloque,                     --6
	sb.orden as bloque_orden,       --7
	sed.pregunta,                   --8
	sed.orden as pregunta_orden,    --9
	(sp.nombre || ' - Opción: ' || sr.valor_tabulado)::text as pregunta_nombre,         --10
	scp.numero as componente_numero,--11
	scp.componente as componente,   --12
	'si'::text as opciones_multiples,      --13
	spr.respuesta as respuesta_codigo, --14
	sr.valor_tabulado,              --15
	spr.orden::integer as respuesta_orden,   --16
    (CASE WHEN sfhd.elemento IS NOT NULL THEN 1 ELSE 0 END)::SMALLINT AS hay_elemento,  --17
--    sfh.concepto as concepto, --18
    --sfhd.elemento as elemento, --19
     '' as tabla_asociada --20
FROM sge_formulario_habilitado sfh
	INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado
														AND CASE WHEN form is not null THEN sfh.formulario_habilitado = form ELSE true end
														AND CASE WHEN elem is not null THEN sfhd.elemento = elem ELSE true end
														AND CASE WHEN enc is not null THEN sfhd.encuesta = enc ELSE true end)
	INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta
									AND CASE WHEN enc_def is not null THEN sed.encuesta_definicion = enc_def ELSE true end
								--PARA INCLUIR SOLO LAS PREGUNTAS QUE FUERON RESPONDIDAS
										AND
											CASE -- SI SE PIDIÓ EL FILTRO SE AGREGA
											WHEN filtro_preguntas THEN
												EXISTS (SELECT  srd.respondido_detalle 
                                                                                                        FROM    sge_respondido_detalle srd
                                                                                                                    INNER JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta)
                                                                                                        WHERE   srd.encuesta_definicion = sed.encuesta_definicion
                                                                                                        AND     sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle
                                                                                                        AND     srd.respuesta_codigo is not null
											)
											ELSE --SI NO SE PIDIÓ NO SE FILTRA
												true
											END
										)
	INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
	INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
	INNER JOIN sge_pregunta_respuesta spr ON (sp.pregunta = spr.pregunta)
	INNER JOIN sge_respuesta sr ON (spr.respuesta = sr.respuesta
							--PARA INCLUIR SOLO LAS OPCIONES DE RESPUESTA QUE FUERON ELEGIDAS
							and
								case -- SI SE PIDIÓ EL FILTRO SE AGREGA
								when filtro_respuestas then
									exists (select srd.respuesta_codigo from sge_respondido_detalle srd
											inner join sge_respondido_encuesta sre on (sre.respondido_encuesta = srd.respondido_encuesta)
											where srd.respuesta_codigo = sr.respuesta
													and sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle
											)
								else --SI NO SE PIDIÓ NO SE FILTRA
									true
								END
							)
WHERE
	sfh.habilitacion = hab
	and ((sp.tabla_asociada is null) OR (sp.tabla_asociada = ''))
	and (componente = 'check' or componente = 'list')
	AND CASE WHEN conc is not null THEN sfh.concepto = conc ELSE true end
ORDER BY encuesta_orden, bloque_orden, pregunta_orden, respuesta_orden;

FOR tabla IN
	SELECT DISTINCT sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion
	FROM sge_formulario_habilitado sfh
		INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfh.formulario_habilitado = sfhd.formulario_habilitado)
		INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta)
		INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
	WHERE sfh.habilitacion = hab AND sp.tabla_asociada != '' AND sp.tabla_asociada IS NOT NULL
  LOOP
	EXECUTE 'SELECT COUNT( ' || tabla.tabla_asociada_codigo || ' ) FROM ' || tabla.tabla_asociada INTO cant_opciones;

	RETURN QUERY EXECUTE
		'SELECT
            (sfhd.encuesta || ''_'' ||
                CASE when sfhd.tipo_elemento IS NOT NULL THEN sfhd.tipo_elemento  || ''_''  ELSE '''' END ||
                sfhd.orden || ''_'' ||
				sed.encuesta_definicion ||
                CASE WHEN (componente = ''check'' OR componente = ''list'') THEN ''_'' || ta.' || tabla.tabla_asociada_codigo || ' ELSE '''' END)::character varying
            AS codigo_columna,	--1
            sfh.habilitacion, --2
			sfhd.encuesta, --3
			sfhd.orden as encuesta_orden, --4
			sed.encuesta_definicion, --5
			sed.bloque, --6
			sb.orden as bloque_orden, --7
			sed.pregunta, --8
			sed.orden as pregunta_orden, --9
			case
				when (componente = ''check'' OR componente = ''list'') then sp.nombre || '' - Opción: '' || ta.' || tabla.tabla_asociada_descripcion || '
				else sp.nombre::text
			end as pregunta_nombre, --10
			scp.numero as componente_numero, --11
			scp.componente as componente, --12
			case
				when (componente = ''check'' OR componente = ''list'') then ''si''
				else ''no''
			end as opciones_multiples, --13
			ta.' || tabla.tabla_asociada_codigo || '::integer as respuesta_codigo, --14
			ta.' || tabla.tabla_asociada_descripcion ||  '::character varying as valor_tabulado, --15
--no hay un registro explícito del orden de las respuestas en las tablas asociadas, se toma el valor del código
			ta.' || tabla.tabla_asociada_codigo || '::integer as respuesta_orden, --16
			(CASE WHEN sfhd.elemento IS NOT NULL THEN 1 ELSE 0 END)::SMALLINT AS hay_elemento,  --17
			--sfh.concepto as concepto, --18
		    --sfhd.elemento as elemento, --19
			sp.tabla_asociada::text --20
		FROM sge_formulario_habilitado sfh
			INNER JOIN sge_formulario_habilitado_detalle sfhd ON (sfhd.formulario_habilitado = sfh.formulario_habilitado
																	and ' || cond_form || '
																	and ' || cond_elem || '
																	and ' || cond_enc || '
																	)
			INNER JOIN sge_encuesta_definicion sed ON (sfhd.encuesta = sed.encuesta
										and ' || cond_preg || '
								--PARA INCLUIR SOLO LAS PREGUNTAS QUE FUERON RESPONDIDAS
										AND
											CASE -- SI SE PIDIÓ EL FILTRO SE AGREGA
											WHEN ' || filtro_preguntas || ' THEN
                                                                                        	EXISTS (SELECT  srd.respondido_detalle
                                                                                                        FROM    sge_respondido_detalle srd
                                                                                                                    INNER JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta)
                                                                                                        WHERE   srd.encuesta_definicion = sed.encuesta_definicion
                                                                                                        AND     sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle
                                                                                                        AND     srd.respuesta_codigo is not null
											)
											ELSE --SI NO SE PIDIÓ NO SE FILTRA
												true
											END
										)
			INNER JOIN sge_bloque sb ON (sed.bloque = sb.bloque)
			INNER JOIN sge_pregunta sp ON (sed.pregunta = sp.pregunta)
			INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
			INNER JOIN ' || tabla.tabla_asociada || ' ta ON (
                                        --PARA INCLUIR SOLO LAS OPCIONES DE RESPUESTA QUE FUERON ELEGIDAS

                                            CASE -- SI SE PIDIÓ EL FILTRO SE AGREGA
                                            WHEN ' || filtro_respuestas || ' THEN
                                                    EXISTS (SELECT  srd.respondido_detalle
                                                            FROM    sge_respondido_detalle srd
                                                                        INNER JOIN sge_respondido_encuesta sre ON (sre.respondido_encuesta = srd.respondido_encuesta)
                                                            WHERE   srd.encuesta_definicion = sed.encuesta_definicion
                                                            AND     sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle
                                                            AND   ' || tabla.tabla_asociada_codigo || ' = srd.respuesta_codigo
                                                            )
                                            ELSE --SI NO SE PIDIÓ NO SE FILTRA
                                                    true
                                            END
                                                )
		WHERE sfh.habilitacion = ' || hab || '
			and (sp.tabla_asociada = ''' || tabla.tabla_asociada || ''')
			and (componente != ''label'') and (componente != ''etiqueta_titulo'') and (componente != ''etiqueta_texto_enriquecido'')

			and case
			when ' || cant_opciones || ' > ' || limite_opciones || '  then
				ta.' || tabla.tabla_asociada_codigo || '::integer in
					(select srd.respuesta_codigo
						from sge_respondido_detalle srd
							inner join sge_respondido_encuesta sre on (sre.respondido_encuesta = srd.respondido_encuesta
																		and sre.formulario_habilitado_detalle = sfhd.formulario_habilitado_detalle)
						where srd.respuesta_codigo = ta.' || tabla.tabla_asociada_codigo || '::integer
							and srd.encuesta_definicion = sed.encuesta_definicion
					)
			else true
			end
			and ' || cond_conc || '
		ORDER BY encuesta_orden, bloque_orden, pregunta_orden, respuesta_orden
			; ';
END LOOP;

RETURN;
END;
$function$
;
