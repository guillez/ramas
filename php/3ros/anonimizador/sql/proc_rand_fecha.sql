CREATE OR REPLACE FUNCTION mask.rand_fecha(IN fecha_ori DATE, IN rango INTEGER) RETURNS DATE AS $$
DECLARE
	direccion INTEGER;
	masked_date DATE;
	dta   INTEGER; -- days to add
BEGIN

        perform setseed(extract(microseconds from now())/100000000);
        dta := trunc( ((random()-0.5)* ((rango+1)*2) )) ;  --obtenemos un offset en dias desde -rango hasta rango
	-- Calculamos la nueva fecha sumando el offset
	masked_date := fecha_ori + dta ;
 	RETURN masked_date;
END;
$$ LANGUAGE plpgsql;

