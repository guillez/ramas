-- Function: plpgsql_call_handler()

-- DROP FUNCTION plpgsql_call_handler();

CREATE OR REPLACE FUNCTION plpgsql_call_handler()
  RETURNS language_handler AS
'$libdir/plpgsql', 'plpgsql_call_handler'
  LANGUAGE 'c' VOLATILE;
ALTER FUNCTION plpgsql_call_handler() OWNER TO postgres;


-- Language: plpgsql

-- DROP LANGUAGE plpgsql;

 CREATE TRUSTED PROCEDURAL LANGUAGE 'plpgsql'
  HANDLER plpgsql_call_handler;
