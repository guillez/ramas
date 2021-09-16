-- Function: sp_upsert_concepto(character varying, character varying, integer, character varying)

-- DROP FUNCTION sp_upsert_concepto(character varying, character varying, integer, character varying);

CREATE OR REPLACE FUNCTION sp_upsert_concepto(_concepto_externo character varying, _descripcion character varying, _sistema integer, _unidad_gestion character varying)
  RETURNS record AS
$BODY$
DECLARE 
ret RECORD;
id_concepto integer;
BEGIN
	UPDATE sge_concepto SET descripcion=_descripcion 
	WHERE concepto_externo = _concepto_externo AND 
	      sistema = _sistema AND
	      unidad_gestion = _unidad_gestion
	RETURNING concepto
	INTO id_concepto;
	
	IF( id_concepto IS NULL) THEN
		RAISE NOTICE 'concepto inexistente, se creó uno nuevo';
		INSERT INTO sge_concepto (descripcion, concepto_externo, sistema, unidad_gestion)
			SELECT _descripcion, _concepto_externo, _sistema, _unidad_gestion
		RETURNING concepto
		INTO id_concepto;
		ret := (id_concepto, 0, 'concepto inexistente, se creó uno nuevo'::TEXT);
	ELSE
		--RAISE NOTICE 'params (%,%, %, %)',_concepto, _descripcion, id_item, _unidad_gestion;
		RAISE NOTICE 'concepto actualizado';
		ret := (id_concepto, 1, 'concepto actualizado'::TEXT);
	END IF;
RETURN ret;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION sp_upsert_concepto(character varying, character varying, integer, character varying)
  OWNER TO postgres;
