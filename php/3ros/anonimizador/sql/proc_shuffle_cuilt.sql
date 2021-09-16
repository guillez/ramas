-- Falta programar la parte
CREATE OR REPLACE FUNCTION mask.shuffle_cuilt(IN rangd INTEGER, IN p_rangh INTEGER, IN cuilt_tomask NUMERIC(11)) RETURNS NUMERIC(11) AS $$
DECLARE
	cuilt_masked NUMERIC(11);
	dnitomask INTEGER;
	dnimasked INTEGER;
	nprimo INTEGER;
	cnt_hops INTEGER;
	cuiltchar varchar(15);
	pre varchar(4);
	post varchar(2);
	rangh INTEGER;
BEGIN
	rangh := p_rangh;
	-- primero extraemos el DNI del CUIT y lo pasamos a INTEGER, tambien guardamos los 2 primeros digitos y el ultimo
	cuiltchar = trim(both ' ' from to_char(cuilt_tomask,'99999999999'));
	pre = substring(cuiltchar from 1 for 2);
	post = substring(cuiltchar from length(cuiltchar) for 1);
	--Nos quedamos con el cuil desde pos 3 hasta len-1, y lo casteamos a int
	dnitomask = to_number(substring(cuiltchar from 3 for length(cuiltchar)-3),'99999999') ;
	-- insert into mensajes values ('dnitokask-'||dnitomask||'-');
	-- Aqui hacemos como si fuera un DNI y lo enmascaramos as usual

	-- Obtenemos el nro primo
	SELECT count(*) INTO nprimo FROM mask.mask_random_values;

        -- Ajustamos el tope superior del rango, para que la distancia entre los extremos sea multiplo del nprimo
        rangh := rangh + (nprimo - (rangh - rangd) % nprimo);

	SELECT randomval INTO cnt_hops FROM mask.mask_random_values WHERE modulo = (dnitomask % nprimo);
	dnimasked := dnitomask + cnt_hops * nprimo;
	if dnimasked > rangh THEN
		dnimasked := rangd + (dnimasked - rangh);
	END IF;
	-- Una vez que enmascaramos volvemos a armar en un NUMERIC(11) el CUIL enmascarado
	-- insert into mensajes values ('dnimasked-'||dnimasked||'-');
	cuilt_masked := pre|| trim(both ' ' from to_char(dnimasked,'99999999')) ||post;
	RETURN cuilt_masked;
END;
$$ LANGUAGE plpgsql;

-- recibe un rango y un cuilt en char(11) y devuelve un cuilt anonimizado en char(11). No recibe sexo porque usa 
-- los mismos 2 caracteres(20, 27,23) del cuilt original en el CUILT anonimizado que devuelve
CREATE OR REPLACE FUNCTION mask.shuffle_cuil_char11(IN rangd INTEGER, IN rangh INTEGER, IN p_cuilt VARCHAR(11), IN p_sexo CHAR(1)) RETURNS VARCHAR(11) AS $$
DECLARE
	v_cuilmasked INTEGER;
	v_dni INTEGER;
	v_cuilmasked2 CHAR(11);
	v_cuil1 CHAR(2);
	v_digito_v INT;
	v_cuil2 CHAR(1);

BEGIN
	IF upper(p_sexo) = 'F' or p_sexo = '2' THEN
		v_cuil1 = '27' ;
	ELSE
		v_cuil1 = '20' ;
	END IF;
	v_dni := CAST(substring(p_cuilt from 3 for 8) AS INTEGER);
	v_cuilmasked := mask.shuffle_dni(rangd, rangh, v_dni);	
	v_digito_v :=  mask.get_dig_verif_by_dni_sexo(v_dni, p_sexo );
	v_cuil2 := CAST(v_digito_v AS CHAR(1));	
	v_cuilmasked2 = v_cuil1 || CAST(v_cuilmasked AS CHAR(8))|| v_cuil2 ;
	
	RETURN v_cuilmasked2;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.shuffle_cuil(IN rangd INTEGER, IN rangh INTEGER, IN p_dni INTEGER, IN p_sexo CHAR(1)) RETURNS INTEGER AS $$
DECLARE
	cuilmasked INTEGER;
BEGIN
	cuilmasked := mask.shuffle_dni(rangd, rangh, p_dni);	
	RETURN cuilmasked;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.shuffle_cuil1(IN rangd INTEGER, IN rangh INTEGER, IN p_dni INTEGER, IN p_sexo CHAR(1)) RETURNS INTEGER AS $$
DECLARE
BEGIN
	IF upper(p_sexo) = 'F' or p_sexo = '2' THEN
		RETURN 27;
	ELSE
		RETURN 20;
	END IF;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.shuffle_cuil2(IN rangd INTEGER, IN rangh INTEGER, IN p_dni INTEGER, IN p_sexo CHAR(1)) RETURNS INTEGER AS $$
DECLARE
	dnimasked INTEGER;
	dig_verif INTEGER;
BEGIN
	dnimasked := mask.shuffle_dni(rangd, rangh, p_dni);	
	dig_verif := mask.get_dig_verif_by_dni_sexo(dnimasked, p_sexo);	
	RETURN dig_verif;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.get_dig_verif_by_dni_sexo_old(IN p_dni INTEGER, IN p_sexo CHAR(1)) RETURNS INTEGER AS $$
DECLARE
	cuilmasked INTEGER;
	cuilaux INTEGER;
	acum INTEGER;
	resto INTEGER;
	resto2 INTEGER;
	d1 INTEGER;
	d2 INTEGER;
	d3 INTEGER;
	d4 INTEGER;
	d5 INTEGER;
	d6 INTEGER;
	d7 INTEGER;
	d8 INTEGER;
BEGIN
	-- Formula para calc digito verificador del CUIL/T: Valor1(2 dig) Valor2(dni) Valor3(dig verificador)
	-- Ejemplo numérico con un número de CUIT, que es 20-17254359-7.
	-- 2 0 1 7 2 5 4 3 5 9
	-- x
	-- 5 4 3 2 7 6 5 4 3 2
	-- ------------------------------------------------
	-- 10 + 00 + 03 + 14 + 14 + 30 + 20 + 12 + 15 + 18 = 136
	-- v1 = 136
	-- 136 mod 11 = 4
	-- v2 = 4
	-- 11 - 4 = 7
	-- Si v3 = 7   => codigo verificador es 7.
	-- Si v3 = 11  => codigo verificador es 0.
	-- Si v3 = 10  => codigo verificador es 9.

	acum := 0;
	IF upper(p_sexo) = 'F' or p_sexo = '2' THEN
		acum := (2 * 5) + (7 * 4) ;
	ELSE
		acum := (2 * 5) + (0 * 4) ;
	END IF;
	cuilaux := p_dni;	
	-- vamos calculando el acumulado, lo hacemos digito a digito porque no hay vectores, y simular un vector con un string
	-- va a ser criptico de entender
	-- extraemos el digito 1 (posicion decena de millon) del DNI y lo multiplicamos por 3
	IF cuilaux > 9999999 THEN
		d1 := trunc(cuilaux/10000000);
		acum := acum + d1 * 3;
		cuilaux := cuilaux - d1 * 10000000;
	END IF;
	-- extraemos el digito 2 (posicion millones) del DNI y lo multiplicamos por 2
	IF cuilaux > 999999 THEN
		d2 := trunc(cuilaux/1000000);
		acum := acum + d2 * 2;
		cuilaux := cuilaux - d2 * 1000000;
	END IF;
	-- extraemos el digito 3 (posicion centenas de mil) del DNI y lo multiplicamos por 7
	IF cuilaux > 99999 THEN
		d3 := trunc(cuilaux/100000);
		acum := acum + d3 * 7;
		cuilaux := cuilaux - d3 * 100000;
	END IF;
	-- extraemos el digito 4 (posicion decenas de mil) del DNI y lo multiplicamos por 6
	IF cuilaux > 9999 THEN
		d4 := trunc(cuilaux/10000);
		acum := acum + d4 * 6;
		cuilaux := cuilaux - d4 * 10000;
	END IF;
	-- extraemos el digito 5 (posicion miles) del DNI y lo multiplicamos por 5
	IF cuilaux > 999 THEN
		d5 := trunc(cuilaux/1000);
		acum := acum + d5 * 5;
		cuilaux := cuilaux - d5 * 1000;
	END IF;
	-- extraemos el digito 6 (posicion centenas) del DNI y lo multiplicamos por 4
	IF cuilaux > 99 THEN
		d6 := trunc(cuilaux/100);
		acum := acum + d6 * 4;
		cuilaux := cuilaux - d6 * 100;
	END IF;
	-- extraemos el digito 7 (posicion decenas) del DNI y lo multiplicamos por 3
	IF cuilaux > 9 THEN
		d7 := trunc(cuilaux/10);
		acum := acum + d7 * 3;
		cuilaux := cuilaux - d7 * 10;
	END IF;
	-- extraemos el digito 8 (posicion unidad) del DNI y lo multiplicamos por 2
	d8 := cuilaux;
	acum := acum + d8 * 2;
	-- Calculamos el modulo 11 y obtenemos el dig verificador
	resto := acum % 11;
	resto2 := 11 - resto;
	IF resto2 = 11 THEN
		RETURN 0 ;
	END IF ;
	IF resto2 = 10 THEN
		RETURN 9 ;
	END IF ;
	RETURN resto2;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.get_dig_verif_by_dni_sexo(IN p_dni INTEGER, IN p_sexo CHAR(1)) RETURNS INTEGER AS $$
DECLARE
	cuilmasked INTEGER;
	cuilaux INTEGER;
	acum INTEGER;
	resto INTEGER;
	resto2 INTEGER;
	d1 INTEGER;
	d2 INTEGER;
	d3 INTEGER;
	d4 INTEGER;
	d5 INTEGER;
	d6 INTEGER;
	d7 INTEGER;
	d8 INTEGER;
BEGIN
	-- Formula para calc digito verificador del CUIL/T: Valor1(2 dig) Valor2(dni) Valor3(dig verificador)
	-- Ejemplo numérico con un número de CUIT, que es 20-17254359-7.
	-- 2 0 1 7 2 5 4 3 5 9
	-- x
	-- 5 4 3 2 7 6 5 4 3 2
	-- ------------------------------------------------
	-- 10 + 00 + 03 + 14 + 14 + 30 + 20 + 12 + 15 + 18 = 136
	-- v1 = 136
	-- 136 mod 11 = 4
	-- v2 = 4
	-- 11 - 4 = 7
	-- Si v3 = 7   => codigo verificador es 7.
	-- Si v3 = 11  => codigo verificador es 0.
	-- Si v3 = 10  => codigo verificador es 9.

	acum := 0;
	IF upper(p_sexo) = 'F' THEN
		acum := (2 * 5) + (7 * 4) ;
	ELSE
		acum := (2 * 5) + (0 * 4) ;
	END IF;
	cuilaux := p_dni;	
	-- vamos calculando el acumulado, lo hacemos digito a digito porque no hay vectores, y simular un vector con un string
	-- va a ser criptico de entender
	-- extraemos el digito 1 (posicion decena de millon) del DNI y lo multiplicamos por 3
	IF cuilaux > 9999999 THEN
		d1 := trunc(cuilaux/10000000);
		acum := acum + d1 * 3;
		cuilaux := cuilaux - d1 * 10000000;
	END IF;
	-- extraemos el digito 2 (posicion millones) del DNI y lo multiplicamos por 2
	IF cuilaux > 999999 THEN
		d2 := trunc(cuilaux/1000000);
		acum := acum + d2 * 2;
		cuilaux := cuilaux - d2 * 1000000;
	END IF;
	-- extraemos el digito 3 (posicion centenas de mil) del DNI y lo multiplicamos por 7
	IF cuilaux > 99999 THEN
		d3 := trunc(cuilaux/100000);
		acum := acum + d3 * 7;
		cuilaux := cuilaux - d3 * 100000;
	END IF;
	-- extraemos el digito 4 (posicion decenas de mil) del DNI y lo multiplicamos por 6
	IF cuilaux > 9999 THEN
		d4 := trunc(cuilaux/10000);
		acum := acum + d4 * 6;
		cuilaux := cuilaux - d4 * 10000;
	END IF;
	-- extraemos el digito 5 (posicion miles) del DNI y lo multiplicamos por 5
	IF cuilaux > 999 THEN
		d5 := trunc(cuilaux/1000);
		acum := acum + d5 * 5;
		cuilaux := cuilaux - d5 * 1000;
	END IF;
	-- extraemos el digito 6 (posicion centenas) del DNI y lo multiplicamos por 4
	IF cuilaux > 99 THEN
		d6 := trunc(cuilaux/100);
		acum := acum + d6 * 4;
		cuilaux := cuilaux - d6 * 100;
	END IF;
	-- extraemos el digito 7 (posicion decenas) del DNI y lo multiplicamos por 3
	IF cuilaux > 9 THEN
		d7 := trunc(cuilaux/10);
		acum := acum + d7 * 3;
		cuilaux := cuilaux - d7 * 10;
	END IF;
	-- extraemos el digito 8 (posicion unidad) del DNI y lo multiplicamos por 2
	d8 := cuilaux;
	acum := acum + d8 * 2;
	-- Calculamos el modulo 11 y obtenemos el dig verificador
	resto := acum % 11;
	resto2 := 11 - resto;
	IF resto2 = 11 THEN
		RETURN 0 ;
	END IF ;
	IF resto2 = 10 THEN
		RETURN 9 ;
	END IF ;
	RETURN resto2;
END;
$$ LANGUAGE plpgsql;
