CREATE OR REPLACE FUNCTION mask.rand_importe(IN importe_ori NUMERIC(12,2), IN porcentaje NUMERIC(4,2)) RETURNS NUMERIC(12,2) AS $$
DECLARE
	importe_masked NUMERIC(12,2);
	delta NUMERIC(12,2);
BEGIN
        perform setseed(extract(microseconds from now())/100000000);
	-- obtenemos el delta maximo, luego lo multiplicamos por un valor en el intervalo (-1 y 1)
	delta := importe_ori * ( porcentaje/100.0);
        importe_masked :=  importe_ori + ((random()-0.5) * ((delta) * 2) ) ;  
 	RETURN importe_masked;
END;
$$ LANGUAGE plpgsql;

