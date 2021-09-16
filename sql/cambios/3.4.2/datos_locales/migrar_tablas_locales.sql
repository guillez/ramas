-- Function: kolla.sp_migrar_tablas_locales()

-- DROP FUNCTION kolla.sp_migrar_tablas_locales();

CREATE OR REPLACE FUNCTION sp_migrar_tablas_locales()
  RETURNS integer AS
$BODY$
DECLARE 

t RECORD;
nombre varchar;

BEGIN

FOR t IN
	--TABLAS DE 3.0.0 o posterior QUE SEAN DEFINIDAS POR EL USUARIO
	SELECT table_name
	FROM information_schema.tables
	WHERE table_schema='kolla'
		AND table_type='BASE TABLE'
		AND NOT (table_name ILIKE 'mgi_%' OR table_name ILIKE 'mgn_%' OR table_name ILIKE 'arau_%'
                    OR table_name ILIKE 'int_guarani_%'
                    OR table_name ILIKE 'mug_%'
                    OR table_name ILIKE 'ing_%'
                )
		AND table_name NOT IN ('sge_bloques', 'sge_documentos_tipos', 'sge_encuestados', 'sge_encuestados_grupos',
				'sge_encuestados_titulos', 'sge_encuesta_habilitada', 'sge_encuestas_atributos', 'sge_encuesta_definicion',
				'sge_encuestas_estilos', 'sge_encuestas_ignoradas', 'sge_encuestas_realizada',
				'sge_encuestas_realizada_encabezado', 'sge_encuestas_realizada_valores', 'sge_encuestas_terminada',
				'sge_grupos_encuestados', 'sge_grupos_encuestas', 'sge_instituciones',
				'sge_componente_pregunta', 'sge_preguntas', 'sge_preguntas_respuestas', 'sge_reportes_exportados',
				'sge_reportes_tipos', 'sge_respuestas',
                'sge_ws_conexiones', 'int_ingenieria_relevamiento', 
                'sge_encuesta_habilitacion_indicadores', 'sge_encuesta_indicadores'
                )	
	EXCEPT
	SELECT table_name
	FROM information_schema.tables
	WHERE table_schema='kolla_new'
		AND table_type='BASE TABLE'

	LOOP
		nombre := t.table_name;
		EXECUTE 'CREATE TABLE kolla_new.' || quote_ident(nombre) || ' 
                AS SELECT * FROM kolla.' || quote_ident(nombre) ;
	END LOOP;
RETURN 0;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE;
ALTER FUNCTION sp_migrar_tablas_locales() OWNER TO postgres;

select * from sp_migrar_tablas_locales();
--drop function sp_migrar_tablas_locales();