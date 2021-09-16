CREATE SEQUENCE mask.userseq START 10001;
CREATE OR REPLACE FUNCTION mask.get_username() RETURNS CHAR AS $$
DECLARE
	username char(8);
BEGIN
	select 'U'||nextval('mask.userseq') INTO username;
	RETURN username;
END;
$$ LANGUAGE plpgsql;
