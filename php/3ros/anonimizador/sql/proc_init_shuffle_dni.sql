-- Esta funcion genera ciertos valores iniciales, para que la funcion shuffle_dni funcione correctamente
-- y genere los mismos valores cada vez que se la invoca con el mismo set de parametros.
-- Hay que obtener el nro primo de alguna forma, quizas extraerlo de la clave
-- Hay que resetear una semilla diferente en cada invocacion
CREATE OR REPLACE FUNCTION mask.init_shuffle_dni(IN dni_desde INTEGER, IN dni_hasta INTEGER) RETURNS INTEGER AS $$
DECLARE
	amplitud INTEGER;
	dni INTEGER;
	i INTEGER;
	nprimo INTEGER;
	randvalmax INTEGER;
	randval INTEGER;
BEGIN
	-- La siguiente linea debe reemplazarse por la generacion del valor primo a partir de una clave secreta
	nprimo := 211;
	amplitud := dni_hasta - dni_desde;
	randvalmax := trunc(amplitud / nprimo);
	DELETE FROM mask.mask_random_values;
	FOR i IN 0 .. nprimo - 1 LOOP
		randval := 1 + trunc(random() * ( randvalmax -1 ));
		INSERT INTO mask.mask_random_values values (i, randval);
	END LOOP;
 	RETURN nprimo;
END;
$$ LANGUAGE plpgsql;
