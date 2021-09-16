CREATE OR REPLACE FUNCTION kolla.respuestas_pregunta(integer)
 RETURNS SETOF record
 LANGUAGE plpgsql
AS $function$
DECLARE
preg_id ALIAS for $1;
datos_pregunta record;

BEGIN

	SELECT DISTINCT sp.pregunta, sp.tabla_asociada, sp.tabla_asociada_codigo, sp.tabla_asociada_descripcion, sp.componente_numero,
					sp.tabla_asociada_orden_campo, sp.tabla_asociada_orden_tipo,
					scp.componente
	INTO datos_pregunta
	FROM sge_pregunta sp
			INNER JOIN sge_componente_pregunta scp ON (sp.componente_numero = scp.numero)
	WHERE sp.pregunta = preg_id;

IF (datos_pregunta.tabla_asociada IS NULL) OR (datos_pregunta.tabla_asociada = '') THEN

RETURN QUERY
	SELECT distinct
		sp.pregunta,                   --1
		sp.nombre as pregunta_nombre,   --2
		scp.numero as componente_numero,--3
		scp.componente as componente,   --4
		case
			when (componente = 'check' OR componente = 'list') then 'si'
			else 'no'
		end as opciones_multiples,      --5
		spr.respuesta as respuesta_codigo, --6
		sr.valor_tabulado,              --7
		spr.orden as respuesta_orden,   --8
		sp.tabla_asociada, 				--9
		sp.tabla_asociada_codigo, 		--10
		sp.tabla_asociada_descripcion	--11

	FROM  sge_pregunta sp
		INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
		LEFT JOIN sge_pregunta_respuesta spr ON (
			CASE
			WHEN componente IN ('radio', 'combo', 'list', 'check', 'localidad', 'combo_autocompletado', 'localidad_y_cp', 'combo_dinamico')
				THEN sp.pregunta
			ELSE
				NULL
			END = spr.pregunta)
		LEFT JOIN sge_respuesta sr ON (spr.respuesta = sr.respuesta)
	WHERE sp.pregunta = preg_id AND
		(componente != 'label') and (componente != 'etiqueta_titulo') and (componente != 'etiqueta_texto_enriquecido')
	ORDER BY respuesta_orden;

ELSE

RETURN QUERY EXECUTE '
		SELECT distinct
			sp.pregunta,                   --1
			sp.nombre as pregunta_nombre,   --2
			scp.numero as componente_numero,--3
			scp.componente as componente,   --4
			case
				when (componente = ''check'' OR componente = ''list'') then ''si''
				else ''no''
			end as opciones_multiples,      --5
			ta.' || datos_pregunta.tabla_asociada_codigo || ' as respuesta_codigo, --6
			ta.' || datos_pregunta.tabla_asociada_descripcion || ' as valor_tabulado,              --7
			0::smallint	as respuesta_orden,   --8
			sp.tabla_asociada, 					--9
			sp.tabla_asociada_codigo, 			--10
			sp.tabla_asociada_descripcion		--11

		FROM  sge_pregunta sp
			INNER JOIN sge_componente_pregunta scp ON (scp.numero = sp.componente_numero)
			LEFT JOIN ' || datos_pregunta.tabla_asociada || ' ta ON ( sp.tabla_asociada = ''' || datos_pregunta.tabla_asociada || ''' )
		WHERE sp.pregunta = ' || preg_id || ' AND
			(componente != ''label'') and (componente != ''etiqueta_titulo'') and (componente != ''etiqueta_texto_enriquecido'')
		ORDER BY respuesta_orden;
	';

end if;

END;
$function$
;
