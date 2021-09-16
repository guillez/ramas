CREATE OR REPLACE FUNCTION mask.rand_apyno() RETURNS VARCHAR(50) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
	apemasked VARCHAR(30);
BEGIN
	perform setseed(extract(microseconds from now())/100000000); 
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	nid := trunc( (random()* canti )) ;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and upper(gender) = 'M';

	SELECT count(*) INTO canti FROM mask.lastnames;
	nid := trunc( (random()* canti )) ;
	SELECT lname INTO apemasked FROM mask.lastnames WHERE lnameid = nid ;

 	RETURN apemasked||' '||namemasked;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.rand_apyno(IN maxlen INTEGER) RETURNS VARCHAR(50) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
	apemasked VARCHAR(30);
BEGIN
	perform setseed(extract(microseconds from now())/100000000); 
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	nid := trunc( (random()* canti )) ;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and upper(gender) = 'M';

	SELECT count(*) INTO canti FROM mask.lastnames;
	nid := trunc( (random()* canti )) ;
	SELECT lname INTO apemasked FROM mask.lastnames WHERE lnameid = nid ;

 	RETURN substring(apemasked||' '||namemasked, 1, maxlen);
END;
$$ LANGUAGE plpgsql;
