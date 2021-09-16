-- Function: sp_upsert_elemento(character varying, character varying, character varying, integer, character varying)

-- DROP FUNCTION sp_upsert_elemento(character varying, character varying, character varying, integer, character varying);

CREATE OR REPLACE FUNCTION sp_upsert_elemento(_elemento_externo character varying, _descripcion character varying, _url character varying, _sistema integer, _unidad_gestion character varying)
  RETURNS record AS
$BODY$
DECLARE 
ret RECORD;

id_elemento integer;

BEGIN

	UPDATE sge_elemento SET descripcion=_descripcion, url_img= _url
	WHERE elemento_externo = _elemento_externo AND 
	      sistema = _sistema AND
              unidad_gestion = _unidad_gestion
	RETURNING elemento INTO id_elemento;
	IF( id_elemento IS NULL) THEN
		RAISE NOTICE 'elemento inexistente, se creó uno nuevo';
		INSERT INTO sge_elemento (descripcion, elemento_externo, url_img, sistema, unidad_gestion)
			SELECT _descripcion, _elemento_externo, _url, _sistema, _unidad_gestion
		RETURNING elemento
		INTO id_elemento;
		ret := (id_elemento, 0, 'elemento inexistente, se creó uno nuevo'::TEXT);
	ELSE
		--RAISE NOTICE 'params (%,%, %, %)',_concepto, _descripcion, id_elemento, _unidad_gestion;
		ret := (id_elemento, 1, 'elemento actualizado'::TEXT);
		RAISE NOTICE 'elemento actualizado';
	END IF;

--asocio el elemento con el concepto, si ya existe tira error y lo capturo
-- No asocio mas con un concepto!
RETURN ret;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION sp_upsert_elemento(character varying, character varying, character varying, integer, character varying)
  OWNER TO postgres;
