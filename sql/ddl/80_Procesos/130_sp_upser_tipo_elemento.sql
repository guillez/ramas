-- Function: sp_upsert_tipo_elemento(character varying, character varying, integer, character varying)

-- DROP FUNCTION sp_upsert_tipo_elemento(character varying, character varying, integer, character varying);

CREATE OR REPLACE FUNCTION sp_upsert_tipo_elemento(_tipo_elemento_externo character varying, _descripcion character varying, _sistema integer, _unidad_gestion character varying)
  RETURNS record AS
$BODY$
DECLARE 
ret RECORD;

id_tipo_elemento integer;

BEGIN

	UPDATE sge_tipo_elemento SET descripcion=_descripcion
	WHERE tipo_elemento_externo = _tipo_elemento_externo AND 
	      sistema = _sistema AND
              unidad_gestion = _unidad_gestion
	RETURNING tipo_elemento INTO id_tipo_elemento;
	IF( id_tipo_elemento IS NULL) THEN
		RAISE NOTICE 'tipo de elemento inexistente, se creó uno nuevo';
		INSERT INTO sge_tipo_elemento (descripcion, tipo_elemento_externo, sistema, unidad_gestion)
			SELECT _descripcion, _tipo_elemento_externo, _sistema, _unidad_gestion
		RETURNING tipo_elemento
		INTO id_tipo_elemento;
		ret := (id_tipo_elemento, 0, 'tipo de elemento inexistente, se creó uno nuevo'::TEXT);
	ELSE
		ret := (id_tipo_elemento, 1, 'tipo de elemento actualizado'::TEXT);
		RAISE NOTICE 'tipo de elemento actualizado';
	END IF;
RETURN ret;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION sp_upsert_tipo_elemento(character varying, character varying, integer, character varying)
  OWNER TO postgres;
