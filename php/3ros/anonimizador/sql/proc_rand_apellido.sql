CREATE OR REPLACE FUNCTION mask.rand_apellido(IN apelltomask VARCHAR(30)) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	apemasked VARCHAR(30);
BEGIN
	IF length(trim(both ' ' from apelltomask)) = 0 THEN
		RETURN apelltomask;
	END IF;
	SELECT count(*) INTO canti FROM mask.lastnames;
	nid := trunc( (random()* canti )) ;
	SELECT lname INTO apemasked FROM mask.lastnames WHERE lnameid = nid ;
 	RETURN apemasked;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.rand_apellido(IN apelltomask VARCHAR(30), IN maxlen INTEGER) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	apemasked VARCHAR(30);
BEGIN
	IF length(trim(both ' ' from apelltomask)) = 0 THEN
 		RETURN substring(apelltomask, 1, maxlen);
	END IF;
	SELECT count(*) INTO canti FROM mask.lastnames;
	nid := trunc( (random()* canti )) ;
	SELECT lname INTO apemasked FROM mask.lastnames WHERE lnameid = nid ;
 	RETURN substring(apemasked, 1, maxlen);
END;
$$ LANGUAGE plpgsql;

