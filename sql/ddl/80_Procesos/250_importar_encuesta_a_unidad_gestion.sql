
--
-- FUNCION PARA IMPORTAR UNA ENCUESTA A UNA UNIDAD DE GESTION: Esta función es la contrapartida de la operación
-- de exportación de encuesta a un archivo de texto. Es decir, primeramente se debe exportar una determinada encuesta
-- a un archivo txt, el mismo contiene la creación de un esquema temporal conteniendo la encuesta en cuestión, y luego
-- se importa a la Unidad de Gestión deseada aportando el txt como entrada. Para lo cual la operación crea el esquema
-- paralelo y posteriormente realiza la invocación a la presente función.
--

CREATE OR REPLACE FUNCTION importar_encuesta_a_unidad_gestion(_unidad_gestion character varying)
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
        aux_tabla_asociada          varchar;
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
    SELECT  pregunta,
            nombre,
            componente_numero,
            tabla_asociada,
            tabla_asociada_codigo,
            tabla_asociada_descripcion,
            tabla_asociada_orden_campo,
            tabla_asociada_orden_tipo,
            descripcion_resumida,
            ayuda,
            oculta,
            visualizacion_horizontal
    FROM    kolla_temporal.sge_pregunta
LOOP
    INSERT INTO sge_pregunta (  nombre, componente_numero, tabla_asociada, tabla_asociada_codigo, tabla_asociada_descripcion,
                                tabla_asociada_orden_campo, tabla_asociada_orden_tipo, unidad_gestion, descripcion_resumida,
                                ayuda, oculta, visualizacion_horizontal)
     VALUES (   p.nombre, p.componente_numero, p.tabla_asociada, p.tabla_asociada_codigo, p.tabla_asociada_descripcion,
                p.tabla_asociada_orden_campo, p.tabla_asociada_orden_tipo, _unidad_gestion, p.descripcion_resumida,
                p.ayuda, p.oculta, p.visualizacion_horizontal);
    SELECT MAX(pregunta) FROM sge_pregunta INTO _new_id_p;

    INSERT INTO _sge_pregunta (new_id, old_id) VALUES (_new_id_p, p.pregunta);
END LOOP;

-- sge_respuesta

CREATE TEMP TABLE _sge_respuesta (new_id integer, old_id integer) ON COMMIT DROP;

FOR r IN
    SELECT  sge_respuesta.valor_tabulado,
            sge_respuesta.respuesta
    FROM    kolla_temporal.sge_respuesta
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
    FROM    kolla_temporal.sge_pregunta_respuesta
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
    SELECT  sge_bloque.nombre,
            sge_bloque.descripcion,
            sge_bloque.orden,
            sge_bloque.bloque
    FROM    kolla_temporal.sge_bloque
LOOP
    INSERT INTO sge_bloque (nombre, descripcion, orden)
    VALUES (b.nombre, b.descripcion, b.orden);
    SELECT MAX(bloque) FROM sge_bloque INTO _new_id_b;

    INSERT INTO _sge_bloque (new_id, old_id) VALUES (_new_id_b, b.bloque);
END LOOP;

-- sge_encuesta_atributo

CREATE TEMP TABLE _sge_encuesta_atributo (new_id integer, old_id integer) ON COMMIT DROP;

FOR ea IN
    SELECT  sge_encuesta_atributo.nombre,
            sge_encuesta_atributo.descripcion,
            sge_encuesta_atributo.texto_preliminar,
            sge_encuesta_atributo.implementada,
            sge_encuesta_atributo.estado,
            sge_encuesta_atributo.encuesta
    FROM    kolla_temporal.sge_encuesta_atributo
LOOP
    -- todas las importaciones de encuestas quedan inicialmente en estado NO implementada
    INSERT INTO sge_encuesta_atributo (nombre, descripcion, texto_preliminar, implementada, estado, unidad_gestion)
    VALUES (ea.nombre, ea.descripcion, ea.texto_preliminar, 'N', ea.estado, _unidad_gestion);
    SELECT MAX(encuesta) FROM sge_encuesta_atributo INTO _new_id_ea;

    INSERT INTO _sge_encuesta_atributo (new_id, old_id) VALUES (_new_id_ea, ea.encuesta);
END LOOP;

-- sge_encuesta_definicion

CREATE TEMP TABLE _sge_encuesta_definicion (new_id integer, old_id integer) ON COMMIT DROP;

FOR ed IN
    SELECT  sge_encuesta_definicion.encuesta_definicion,
            sge_encuesta_definicion.encuesta,
            sge_encuesta_definicion.bloque,
            sge_encuesta_definicion.pregunta,
            sge_encuesta_definicion.orden,
            sge_encuesta_definicion.obligatoria
    FROM    kolla_temporal.sge_encuesta_definicion
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

FOR pd IN
    SELECT  sge_pregunta_dependencia.pregunta_dependencia,
            sge_pregunta_dependencia.encuesta_definicion
    FROM    kolla_temporal.sge_pregunta_dependencia
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

FOR dd IN
    SELECT  sge_pregunta_dependencia_definicion.dependencia_definicion,
            sge_pregunta_dependencia_definicion.pregunta_dependencia,
            sge_pregunta_dependencia_definicion.bloque,
            sge_pregunta_dependencia_definicion.pregunta,
            sge_pregunta_dependencia_definicion.condicion,
            sge_pregunta_dependencia_definicion.valor,
            sge_pregunta_dependencia_definicion.accion,
            sge_pregunta_dependencia_definicion.encuesta_definicion
    FROM    kolla_temporal.sge_pregunta_dependencia_definicion
LOOP
    new_id_pregunta_dependencia	 := (   SELECT	_sge_pregunta_dependencia.new_id
                                        FROM	_sge_pregunta_dependencia
                                        WHERE	_sge_pregunta_dependencia.old_id = dd.pregunta_dependencia);

    new_id_bloque	 := (	SELECT	_sge_bloque.new_id
                                FROM	_sge_bloque
                                WHERE	_sge_bloque.old_id = dd.bloque);

    new_id_pregunta  := (   SELECT	_sge_pregunta.new_id
                            FROM	_sge_pregunta
                            WHERE	_sge_pregunta.old_id = dd.pregunta);

    tipo_componente_pregunta := (   SELECT  sge_componente_pregunta.tipo
                                    FROM    kolla_temporal.sge_componente_pregunta,
                                            kolla_temporal.sge_encuesta_definicion,
                                            kolla_temporal.sge_pregunta_dependencia,
                                            kolla_temporal.sge_pregunta
                                    WHERE   dd.pregunta_dependencia = kolla_temporal.sge_pregunta_dependencia.pregunta_dependencia
                                    AND     kolla_temporal.sge_pregunta_dependencia.encuesta_definicion = kolla_temporal.sge_encuesta_definicion.encuesta_definicion
                                    AND     kolla_temporal.sge_encuesta_definicion.pregunta = kolla_temporal.sge_pregunta.pregunta
                                    AND     kolla_temporal.sge_componente_pregunta.numero = kolla_temporal.sge_pregunta.componente_numero);

  -- Esto es para el caso de si se uso una tabla asociada. Porque en las consultas que se evaluan debajo
  -- no aparecen las preguntas que usaron tablas asociadas. Entonces en la condición del if se verifica
  -- si la pregunta contenía tabla asociada, caso afirmativo se asigna el dd.valor.

    aux_tabla_asociada := ( SELECT sge_pregunta.tabla_asociada
                            FROM kolla_temporal.sge_pregunta_dependencia_definicion
                                JOIN kolla_temporal.sge_pregunta_dependencia ON (sge_pregunta_dependencia.pregunta_dependencia = sge_pregunta_dependencia_definicion.pregunta_dependencia)
                                JOIN kolla_temporal.sge_encuesta_definicion ON (sge_encuesta_definicion.encuesta_definicion = sge_pregunta_dependencia.encuesta_definicion)
                                JOIN kolla_temporal.sge_pregunta ON (sge_pregunta.pregunta = sge_encuesta_definicion.pregunta)
                            WHERE   (sge_pregunta_dependencia_definicion.dependencia_definicion = dd.dependencia_definicion)
                            AND     (sge_pregunta_dependencia_definicion.pregunta = dd.pregunta) );

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
    new_id_encuesta_definicion := ( SELECT	_sge_encuesta_definicion.new_id
                                    FROM	_sge_encuesta_definicion
                                    WHERE	_sge_encuesta_definicion.old_id = dd.encuesta_definicion);

    INSERT INTO sge_pregunta_dependencia_definicion (pregunta_dependencia, bloque, pregunta, condicion, valor, accion, encuesta_definicion)
    VALUES (new_id_pregunta_dependencia, new_id_bloque, new_id_pregunta, dd.condicion, new_id_valor, dd.accion, new_id_encuesta_definicion);
END LOOP;

-- sge_pregunta_cascada

FOR pc IN
    SELECT  sge_pregunta_cascada.pregunta_disparadora,
            sge_pregunta_cascada.pregunta_receptora
    FROM    kolla_temporal.sge_pregunta_cascada
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
