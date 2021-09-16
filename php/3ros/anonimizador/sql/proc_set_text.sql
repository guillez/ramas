CREATE OR REPLACE FUNCTION mask.set_text(IN texto VARCHAR(40)) RETURNS VARCHAR(40) AS $$
DECLARE
BEGIN
	RETURN texto;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.concat_txt_txt(IN texto1 VARCHAR(100), IN texto2 VARCHAR(100)) RETURNS VARCHAR(200) AS $$
DECLARE
BEGIN
	RETURN texto1 || texto2;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.concat_txt_txt(IN texto1 VARCHAR(100), IN texto2 VARCHAR(100), IN maxlen INTEGER) RETURNS VARCHAR(200) AS $$
DECLARE
	resultado VARCHAR(200);
BEGIN
	resultado := texto1 || texto2;
	RETURN substring(resultado, 1, maxlen);
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.concat_txt_int(IN texto1 VARCHAR(100), IN numero INTEGER) RETURNS VARCHAR(120) AS $$
DECLARE
	numero_string VARCHAR(20);
BEGIN
	numero_string := numero::VARCHAR(20);
	RETURN texto1 || numero_string;
END;
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.concat_txt_int(IN texto1 VARCHAR(100), IN numero INTEGER, IN maxlen INTEGER) RETURNS VARCHAR(120) AS $$
DECLARE
	resultado VARCHAR(200);
	numero_string VARCHAR(20);
BEGIN
	numero_string := numero::VARCHAR(20);
	resultado := texto1 || numero_string;
	RETURN substring(resultado, 1, maxlen);
END;
$$ LANGUAGE plpgsql;

DROP SEQUENCE if exists mask.maskseq1;
CREATE SEQUENCE mask.maskseq1;
DROP SEQUENCE if exists mask.maskseq2;
CREATE SEQUENCE mask.maskseq2;
DROP SEQUENCE if exists mask.maskseq3;
CREATE SEQUENCE mask.maskseq3;
DROP SEQUENCE if exists mask.maskseq4;
CREATE SEQUENCE mask.maskseq4;
DROP SEQUENCE if exists mask.maskseq5;
CREATE SEQUENCE mask.maskseq5;
CREATE OR REPLACE FUNCTION mask.concat_txt_seq(IN texto VARCHAR(100), seqname char(20)) returns VARCHAR(120) as $$
DECLARE 
	resultado VARCHAR(120);
 	nroserial INTEGER;
	seqname2 regclass;
BEGIN
	seqname2 = seqname::regclass;
	nroserial := nextval(seqname2);
	resultado := texto || nroserial;
 	return resultado;
END
$$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION mask.translate(IN texto_desde VARCHAR(40), IN texto_hasta VARCHAR(40), IN caracter1 CHAR(1)) RETURNS CHAR(1) AS $$
DECLARE
	caracter2 CHAR(1);
	posicion INTEGER;
BEGIN
	IF length(texto_desde) != length(texto_hasta)  THEN
		RETURN caracter1;
	END IF;
	posicion := position(caracter1 in texto_desde);
	if posicion > 0 THEN
		caracter2 := substring(texto_hasta from posicion for 1);
	ELSE	
		-- devolvemos sin cambios el caracter de entrada
		caracter2 := caracter1;
	END IF; 
	RETURN caracter2;
END
$$ LANGUAGE plpgsql;
