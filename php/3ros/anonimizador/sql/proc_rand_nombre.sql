CREATE OR REPLACE FUNCTION mask.rand_nombre(nametomask VARCHAR(30)) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
                RETURN nametomask;
        END IF;

	perform setseed(extract(microseconds from now())/100000000); 
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	nid := trunc( (random()* canti )) ;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = 'M';
 	RETURN namemasked;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.rand_nombre(nametomask VARCHAR(30), IN maxlen INTEGER) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
 		RETURN substring(nametomask, 1, maxlen);
        END IF;
	perform setseed(extract(microseconds from now())/100000000); 
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	nid := trunc( (random()* canti )) ;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = 'M';
 	RETURN substring(namemasked, 1, maxlen);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.rand_nombre_x_sexo(nametomask VARCHAR(30), IN sexo CHAR(1)) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
                RETURN nametomask;
        END IF;

	perform setseed(extract(microseconds from now())/100000000); 
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	nid := trunc( (random()* canti )) ;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = upper(sexo);
 	RETURN namemasked;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.rand_nombre_x_sexo(nametomask VARCHAR(30), IN sexo CHAR(1), IN maxlen INTEGER) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
 		RETURN substring(nametomask, 1, maxlen);
        END IF;
	perform setseed(extract(microseconds from now())/100000000); 
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	nid := trunc( (random()* canti )) ;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = upper(sexo);
 	RETURN substring(namemasked, 1, maxlen);
END;
$$ LANGUAGE plpgsql;

