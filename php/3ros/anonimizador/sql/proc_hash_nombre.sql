CREATE OR REPLACE FUNCTION mask.hash_nombre(IN nametomask VARCHAR(30)) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
	first_letter_digit INTEGER;
	second_letter_digit INTEGER;
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
                RETURN nametomask;
        END IF;
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	first_letter_digit := ASCII(UPPER(substring(nametomask from 1 for 1)));
	second_letter_digit := ASCII(UPPER(substring(nametomask from 2 for 1)));
	nid := ((first_letter_digit * 26 + second_letter_digit) % canti ) + 1;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = 'M';
 	RETURN namemasked;
END;
$$ LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION mask.hash_nombre(IN nametomask VARCHAR(30), IN maxlen INTEGER) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
	first_letter_digit INTEGER;
	second_letter_digit INTEGER;
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
                RETURN nametomask;
        END IF;
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	first_letter_digit := ASCII(substring(nametomask from 1 for 1));
	second_letter_digit := ASCII(substring(nametomask from 2 for 1));
	nid := ((first_letter_digit * 26 + second_letter_digit) % canti ) + 1;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = 'M';
	RETURN substring(namemasked, 1, maxlen);
END;
$$ LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION mask.hash_nombre_x_sexo(IN nametomask VARCHAR(30), IN sexo CHAR(1) ) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
	first_letter_digit INTEGER;
	second_letter_digit INTEGER;
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
                RETURN nametomask;
        END IF;
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	first_letter_digit := ASCII(substring(nametomask from 1 for 1));
	second_letter_digit := ASCII(substring(nametomask from 2 for 1));
	nid := ((first_letter_digit * 26 + second_letter_digit) % canti ) + 1;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = UPPER(sexo);
 	RETURN namemasked;
END;
$$ LANGUAGE plpgsql;
CREATE OR REPLACE FUNCTION mask.hash_nombre_x_sexo(IN nametomask VARCHAR(30), IN sexo CHAR(1),IN maxlen INTEGER ) RETURNS VARCHAR(30) AS $$
DECLARE
	canti INTEGER;
	nid   INTEGER;
	namemasked VARCHAR(30);
	first_letter_digit INTEGER;
	second_letter_digit INTEGER;
BEGIN
        IF length(trim(both ' ' from nametomask )) = 0 THEN
                RETURN nametomask;
        END IF;
	SELECT max(fnameid) INTO canti FROM mask.firstnames;
	first_letter_digit := ASCII(substring(nametomask from 1 for 1));
	second_letter_digit := ASCII(substring(nametomask from 2 for 1));
	nid := ((first_letter_digit * 26 + second_letter_digit) % canti ) + 1;
	SELECT fname INTO namemasked FROM mask.firstnames WHERE fnameid = nid and gender = UPPER(sexo);
	RETURN substring(namemasked, 1, maxlen);
END;
$$ LANGUAGE plpgsql;
