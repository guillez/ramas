--
-- FUNCION PARA COPIAR UNA ENCUESTA A OTRA UNIDAD DE GESTION: Se copia toda la definición, así como también las preguntas dependientes.
-- Incluyendo las preguntas, respuestas, bloques y la estructura y definicion de la encuesta.
--

CREATE OR REPLACE FUNCTION copiar_encuesta_a_unidad_gestion(_encuesta integer, _unidad_gestion character varying)
 RETURNS integer AS
$BODY$

DECLARE 
	new_id_pregunta             integer;
	new_id_respuesta1           integer;
	new_id_respuesta            varchar;
	new_id_bloque               integer;
	new_id_encuesta             integer;
	new_id_encuesta_definicion  integer;
	new_id_pregunta_dependencia integer;
        new_id_pregunta_disparadora integer;
        new_id_pregunta_receptora   integer;
        aux_tabla_asociada      varchar;
	id                          varchar;
	arreglo                     varchar[];
	resultado                   varchar[];
	new_id_valor                varchar;
	_new_id_p                   integer;
        _new_id_r                   integer;
        _new_id_b                   integer;
        _new_id_ea                  integer;
        _new_id_ed                  integer;
        _new_id_pd                  integer;
        id_tabla_asociada           integer;
        id_tabla_externa            integer;
        tabla                       integer;
        tipo_componente_pregunta    character(1);
        p   record;
	r   record;
	b   record;
	pr  record;
	ea  record;
	ed  record;
	pd  record;
	dd  record;
        pc  record;

BEGIN

-- sge_pregunta

CREATE TEMP TABLE _sge_pregunta (new_id integer, old_id integer) ON COMMIT DROP;
FOR p IN 
    SELECT	sge_pregunta.nombre,
            sge_pregunta.componente_numero,
            sge_pregunta.tabla_asociada,
            sge_pregunta.tabla_asociada_codigo,
            sge_pregunta.tabla_asociada_descripcion,
            sge_pregunta.tabla_asociada_orden_campo,
            sge_pregunta.tabla_asociada_orden_tipo,
            sge_pregunta.descripcion_resumida,
            sge_pregunta.pregunta,
            sge_pregunta.ayuda,
            sge_pregunta.oculta,
            sge_pregunta.visualizacion_horizontal
    FROM	sge_pregunta
    WHERE	sge_pregunta.pregunta IN (
            SELECT	sge_encuesta_definicion.pregunta
            FROM	sge_encuesta_definicion
            WHERE	sge_encuesta_definicion.encuesta = _encuesta)
LOOP
    INSERT INTO sge_pregunta (  nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion,
                                tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida, ayuda, oculta, visualizacion_horizontal)
     VALUES (   p.nombre, p.componente_numero, p.tabla_asociada, p.tabla_asociada_codigo, p.tabla_asociada_descripcion,
                p.tabla_asociada_orden_campo, p.tabla_asociada_orden_tipo, _unidad_gestion, p.descripcion_resumida, p.ayuda, p.oculta, p.visualizacion_horizontal);

    --Si la pregunta tiene tabla asociada se debe ver si se la asocia a la UG como tabla externa o como asociada
    IF (p.tabla_asociada <> '') THEN
        IF (substring(p.tabla_asociada from 1 for 3) = 'ta_') THEN
            
            tabla :=   (SELECT  sge_tabla_asociada.tabla_asociada
                        FROM    sge_tabla_asociada
                        WHERE   sge_tabla_asociada.unidad_gestion = _unidad_gestion
                        AND     sge_tabla_asociada.tabla_asociada_nombre = p.tabla_asociada);

            IF (tabla IS NULL) THEN
                
                --Se inserta como Tabla Asociada
                SELECT MAX(tabla_asociada) FROM sge_tabla_asociada INTO id_tabla_asociada;

                INSERT INTO sge_tabla_asociada (tabla_asociada, unidad_gestion, tabla_asociada_nombre)
                VALUES      (id_tabla_asociada + 1, _unidad_gestion, p.tabla_asociada);
            END IF;
        ELSE
            
            tabla :=   (SELECT  sge_tabla_externa.tabla_externa
                        FROM    sge_tabla_externa
                        WHERE   sge_tabla_externa.unidad_gestion = _unidad_gestion
                        AND     sge_tabla_externa.tabla_externa_nombre = p.tabla_asociada);

            IF (tabla IS NULL) THEN
                
                --Se inserta como Tabla Externa
                SELECT MAX(tabla_externa) FROM sge_tabla_externa INTO id_tabla_externa;

                INSERT INTO sge_tabla_externa (tabla_externa, unidad_gestion, tabla_externa_nombre)
                VALUES      (id_tabla_externa + 1, _unidad_gestion, p.tabla_asociada);
            END IF;
        END IF;
    END IF;
    
    SELECT MAX(pregunta) FROM sge_pregunta INTO _new_id_p;

    INSERT INTO _sge_pregunta (new_id, old_id) VALUES (_new_id_p, p.pregunta);
END LOOP;

-- sge_respuesta

CREATE TEMP TABLE _sge_respuesta (new_id integer, old_id integer) ON COMMIT DROP;
FOR r IN 
    SELECT	sge_respuesta.valor_tabulado,
            sge_respuesta.respuesta
    FROM	sge_respuesta
    WHERE	sge_respuesta.respuesta IN (
            SELECT	sge_pregunta_respuesta.respuesta
            FROM	sge_pregunta_respuesta,
                    sge_encuesta_definicion
            WHERE	sge_pregunta_respuesta.pregunta = sge_encuesta_definicion.pregunta
            AND	sge_encuesta_definicion.encuesta = _encuesta)
LOOP
    INSERT INTO sge_respuesta (valor_tabulado, unidad_gestion)
    VALUES (r.valor_tabulado, _unidad_gestion);
    SELECT MAX(respuesta) FROM sge_respuesta INTO _new_id_r;

    INSERT INTO _sge_respuesta (new_id, old_id) VALUES (_new_id_r, r.respuesta);
END LOOP;

-- sge_pregunta_respuesta

FOR pr IN	
    SELECT  sge_pregunta_respuesta.pregunta,
            sge_pregunta_respuesta.respuesta,
            sge_pregunta_respuesta.orden
    FROM    sge_pregunta_respuesta
    WHERE   sge_pregunta_respuesta.pregunta IN (
            SELECT	sge_encuesta_definicion.pregunta
            FROM	sge_encuesta_definicion
            WHERE	sge_encuesta_definicion.encuesta = _encuesta)
LOOP
    new_id_pregunta  := (   SELECT  _sge_pregunta.new_id
                            FROM    _sge_pregunta
                            WHERE   _sge_pregunta.old_id = pr.pregunta);

    new_id_respuesta1 := (  SELECT  _sge_respuesta.new_id
                            FROM    _sge_respuesta
                            WHERE   _sge_respuesta.old_id = pr.respuesta);

    INSERT INTO sge_pregunta_respuesta 	(pregunta, 	  respuesta, 	    orden)
    VALUES 				(new_id_pregunta, new_id_respuesta1, pr.orden);
END LOOP;

-- sge_bloque

CREATE TEMP TABLE _sge_bloque (new_id integer, old_id integer) ON COMMIT DROP;

FOR b IN
	SELECT	sge_bloque.nombre,
		sge_bloque.descripcion,
		sge_bloque.orden,
		sge_bloque.bloque
	FROM	sge_bloque
	WHERE	sge_bloque.bloque IN (
		SELECT	sge_encuesta_definicion.bloque
		FROM	sge_encuesta_definicion
		WHERE	sge_encuesta_definicion.encuesta = _encuesta)
LOOP
    INSERT INTO sge_bloque (nombre, descripcion, orden)
    VALUES (b.nombre, b.descripcion, b.orden);
    SELECT MAX(bloque) FROM sge_bloque INTO _new_id_b;

    INSERT INTO _sge_bloque (new_id, old_id) VALUES (_new_id_b, b.bloque);
END LOOP;

-- sge_encuesta_atributo

CREATE TEMP TABLE _sge_encuesta_atributo (new_id integer, old_id integer) ON COMMIT DROP;

FOR ea IN
	SELECT	sge_encuesta_atributo.nombre,
		sge_encuesta_atributo.descripcion,
		sge_encuesta_atributo.texto_preliminar,
		sge_encuesta_atributo.implementada,
		sge_encuesta_atributo.estado,
		sge_encuesta_atributo.encuesta
	FROM	sge_encuesta_atributo
	WHERE	sge_encuesta_atributo.encuesta = _encuesta
LOOP
    -- todas las copias de encuestas quedan inicialmente en estado NO implementada
    INSERT INTO sge_encuesta_atributo (nombre, descripcion, texto_preliminar, implementada, estado, unidad_gestion)
    VALUES (ea.nombre, ea.descripcion, ea.texto_preliminar, 'N', ea.estado, _unidad_gestion); 
    SELECT MAX(encuesta) FROM sge_encuesta_atributo INTO _new_id_ea;

    INSERT INTO _sge_encuesta_atributo (new_id, old_id) VALUES (_new_id_ea, ea.encuesta);
END LOOP;

   
-- sge_encuesta_definicion

CREATE TEMP TABLE _sge_encuesta_definicion (new_id integer, old_id integer) ON COMMIT DROP;
FOR ed IN                       SELECT	sge_encuesta_definicion.encuesta_definicion,
					sge_encuesta_definicion.encuesta,
					sge_encuesta_definicion.bloque,
					sge_encuesta_definicion.pregunta,
					sge_encuesta_definicion.orden,
					sge_encuesta_definicion.obligatoria
				FROM	sge_encuesta_definicion
				WHERE	sge_encuesta_definicion.encuesta = _encuesta
LOOP
	new_id_pregunta  := (	SELECT	_sge_pregunta.new_id
				FROM	_sge_pregunta
				WHERE	_sge_pregunta.old_id = ed.pregunta);

	new_id_bloque	 := (	SELECT	_sge_bloque.new_id
				FROM	_sge_bloque
				WHERE	_sge_bloque.old_id = ed.bloque);

	new_id_encuesta	 := (	SELECT	_sge_encuesta_atributo.new_id
				FROM	_sge_encuesta_atributo
				WHERE	_sge_encuesta_atributo.old_id = ed.encuesta);

	INSERT INTO sge_encuesta_definicion 	(encuesta, 	  bloque, 	 pregunta, 	  orden,      obligatoria)
	VALUES 					(new_id_encuesta, new_id_bloque, new_id_pregunta, ed.orden, ed.obligatoria);
	SELECT MAX(encuesta_definicion) FROM sge_encuesta_definicion INTO _new_id_ed;

	INSERT INTO _sge_encuesta_definicion (new_id, old_id) VALUES (_new_id_ed, ed.encuesta_definicion);
END LOOP;


-- sge_pregunta_dependencia

CREATE TEMP TABLE _sge_pregunta_dependencia (new_id integer, old_id integer) ON COMMIT DROP;

FOR pd IN       SELECT	sge_pregunta_dependencia.pregunta_dependencia,
			sge_pregunta_dependencia.encuesta_definicion
		FROM	sge_pregunta_dependencia
		WHERE	sge_pregunta_dependencia.encuesta_definicion IN (
			SELECT	sge_encuesta_definicion.encuesta_definicion
			FROM	sge_encuesta_definicion
			WHERE	sge_encuesta_definicion.encuesta = _encuesta)
LOOP
	new_id_encuesta_definicion	 := (	SELECT	_sge_encuesta_definicion.new_id
				FROM	_sge_encuesta_definicion
				WHERE	_sge_encuesta_definicion.old_id = pd.encuesta_definicion);

	INSERT INTO sge_pregunta_dependencia 	(encuesta_definicion)
	VALUES 					(new_id_encuesta_definicion);
	SELECT MAX(pregunta_dependencia) FROM sge_pregunta_dependencia INTO _new_id_pd;

	INSERT INTO _sge_pregunta_dependencia (new_id, old_id) VALUES (_new_id_pd, pd.pregunta_dependencia);
END LOOP;


-- sge_pregunta_dependencia_definicion

FOR dd IN	SELECT	sge_pregunta_dependencia_definicion.dependencia_definicion,
			sge_pregunta_dependencia_definicion.pregunta_dependencia,
			sge_pregunta_dependencia_definicion.bloque,
			sge_pregunta_dependencia_definicion.pregunta,
			sge_pregunta_dependencia_definicion.condicion,
			sge_pregunta_dependencia_definicion.valor,
			sge_pregunta_dependencia_definicion.accion,
			sge_pregunta_dependencia_definicion.encuesta_definicion
		FROM	sge_pregunta_dependencia_definicion
		WHERE	sge_pregunta_dependencia_definicion.pregunta_dependencia IN (
			SELECT	sge_pregunta_dependencia.pregunta_dependencia
			FROM	sge_pregunta_dependencia
			WHERE	sge_pregunta_dependencia.encuesta_definicion IN (
				SELECT	sge_encuesta_definicion.encuesta_definicion
				FROM	sge_encuesta_definicion
				WHERE	sge_encuesta_definicion.encuesta = _encuesta))
LOOP
	new_id_pregunta_dependencia	 := (	SELECT	_sge_pregunta_dependencia.new_id
				FROM	_sge_pregunta_dependencia
				WHERE	_sge_pregunta_dependencia.old_id = dd.pregunta_dependencia);
				
	new_id_bloque	 := (	SELECT	_sge_bloque.new_id
				FROM	_sge_bloque
				WHERE	_sge_bloque.old_id = dd.bloque);

	new_id_pregunta  := (	SELECT	_sge_pregunta.new_id
				FROM	_sge_pregunta
				WHERE	_sge_pregunta.old_id = dd.pregunta);

	tipo_componente_pregunta := (   SELECT  sge_componente_pregunta.tipo
                                        FROM    sge_componente_pregunta,
                                                sge_encuesta_definicion,
                                                sge_pregunta_dependencia,
                                                sge_pregunta
                                        WHERE   dd.pregunta_dependencia = sge_pregunta_dependencia.pregunta_dependencia
                                        AND	sge_pregunta_dependencia.encuesta_definicion = sge_encuesta_definicion.encuesta_definicion
                                        AND     sge_encuesta_definicion.pregunta = sge_pregunta.pregunta
                                        AND     sge_componente_pregunta.numero = sge_pregunta.componente_numero);

  -- Esto es para el caso de si se uso una tabla asociada. Porque en las consultas que se evaluan debajo
  -- no aparecen las preguntas que usaron tablas asociadas. Entonces en la condición del if se verifica
  -- si la pregunta contenía tabla asociada, caso afirmativo se asigna el dd.valor.

  aux_tabla_asociada := ( SELECT sge_pregunta.tabla_asociada
                            FROM sge_pregunta_dependencia_definicion
                                JOIN sge_pregunta_dependencia ON (sge_pregunta_dependencia.pregunta_dependencia = sge_pregunta_dependencia_definicion.pregunta_dependencia)
                                JOIN sge_encuesta_definicion ON (sge_encuesta_definicion.encuesta_definicion = sge_pregunta_dependencia.encuesta_definicion)
                                JOIN sge_pregunta ON (sge_pregunta.pregunta = sge_encuesta_definicion.pregunta)
                            WHERE (sge_encuesta_definicion.encuesta = _encuesta)
                                AND (sge_pregunta_dependencia_definicion.dependencia_definicion = dd.dependencia_definicion)
                                AND (sge_pregunta_dependencia_definicion.pregunta = dd.pregunta) );

	IF (tipo_componente_pregunta = 'A' OR tipo_componente_pregunta = 'E' OR aux_tabla_asociada != '') THEN
            new_id_valor := dd.valor;
        ELSE
            arreglo := string_to_array(dd.valor, ',');
            resultado := array[]::varchar[];

            FOREACH id IN ARRAY arreglo
            LOOP
                    new_id_respuesta  := (	SELECT	_sge_respuesta.new_id
                                            FROM	_sge_respuesta
                                            WHERE	_sge_respuesta.old_id::varchar = id);

                    resultado := array_append(resultado, new_id_respuesta);
            END LOOP;

            new_id_valor := array_to_string(resultado, ',');
	END IF;	
	new_id_encuesta_definicion := (	SELECT	_sge_encuesta_definicion.new_id
                                        FROM	_sge_encuesta_definicion
                                        WHERE	_sge_encuesta_definicion.old_id = dd.encuesta_definicion);

	INSERT INTO sge_pregunta_dependencia_definicion (pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion)
	VALUES (new_id_pregunta_dependencia, new_id_bloque, new_id_pregunta, dd.condicion, new_id_valor, dd.accion, new_id_encuesta_definicion);
END LOOP;

-- sge_pregunta_cascada

FOR pc IN
    SELECT  sge_pregunta_cascada.pregunta_disparadora,
            sge_pregunta_cascada.pregunta_receptora
    FROM    sge_pregunta_cascada
    WHERE   sge_pregunta_cascada.pregunta_disparadora IN (
            SELECT	sge_encuesta_definicion.pregunta
            FROM	sge_encuesta_definicion
            WHERE	sge_encuesta_definicion.encuesta = _encuesta)
LOOP
    new_id_pregunta_disparadora := (SELECT  _sge_pregunta.new_id
                                    FROM    _sge_pregunta
                                    WHERE   _sge_pregunta.old_id = pc.pregunta_disparadora);

    new_id_pregunta_receptora := (  SELECT  _sge_pregunta.new_id
                                    FROM    _sge_pregunta
                                    WHERE   _sge_pregunta.old_id = pc.pregunta_receptora);

    INSERT INTO sge_pregunta_cascada 	(pregunta_disparadora, pregunta_receptora)
    VALUES 				(new_id_pregunta_disparadora, new_id_pregunta_receptora);
END LOOP;

RETURN 1;

END;

$BODY$
LANGUAGE plpgsql VOLATILE
COST 100;
