CREATE OR REPLACE FUNCTION mask.rand_dni(IN dni_desde INTEGER, IN dni_hasta INTEGER) RETURNS INTEGER AS $$
DECLARE
	rango INTEGER;
	dni INTEGER;
BEGIN
	rango := dni_hasta - dni_desde;
	dni := dni_desde + trunc( (random()* rango )) ;
 	RETURN dni;
END;
$$ LANGUAGE plpgsql;

