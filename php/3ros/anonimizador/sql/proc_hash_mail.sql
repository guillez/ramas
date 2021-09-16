CREATE OR REPLACE FUNCTION mask.hash_mail(IN nombre CHAR, IN apellido CHAR, IN dominio CHAR) RETURNS CHAR AS $$
DECLARE
	mail VARCHAR(120);
BEGIN
--	mail := nombre||"."||apellido||"@"||dominio;
	RETURN nombre||'.'||apellido||'@'||dominio;
END;
$$ LANGUAGE plpgsql;

