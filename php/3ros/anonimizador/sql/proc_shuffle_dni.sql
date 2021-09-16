-- Falta programar la parte que altera el rango para que sea multiplo del nro primo
CREATE OR REPLACE FUNCTION mask.shuffle_dni(IN rangd INTEGER, IN p_rangh INTEGER, IN dnitomask INTEGER) RETURNS INTEGER AS $$
DECLARE
	dnimasked INTEGER;
	nprimo INTEGER;
	cnt_hops INTEGER;
	rangh INTEGER;
BEGIN
	rangh := p_rangh;
	-- Obtenemos el nro primo contando la cantidad de rows en la tabla mask_random_values, ya que hay tantas
	-- rows como indique el nro primo. Cada Row en esa tabla tiene un nro random entre 1 y un valor maximo
	SELECT count(*) INTO nprimo FROM mask.mask_random_values;

	-- Ajustamos el tope superior del rango, para que la distancia entre los extremos sea multiplo del nprimo
	rangh := rangh + (nprimo - (rangh - rangd) % nprimo);

	-- Si el valor a enmascarar esta fuera del rango, devolvemos el tope inferior
	IF dnitomask < rangd OR dnitomask > rangh THEN
		RETURN rangd;
	END IF; 
	-- obtenemos la cantidad de hops que le vamos a hacer a nuestro dni original, para eso calculamos su modulo primo
	-- y obtenemos de la tabla mask_random_values el valor random calculado para ese modulo
	SELECT randomval INTO cnt_hops FROM mask.mask_random_values WHERE modulo = (dnitomask % nprimo);

	-- Calculamos el nuevo DNI saltando cnt_hops veces por los numeros que tienen el mismo resto de dividirlos por primo
	dnimasked := dnitomask + cnt_hops * nprimo;

	-- Si el dni resultante se pasa de la cota maxima, lo calculamos circularmente a partir del inicio del rango
	if dnimasked > rangh THEN
		dnimasked := rangd + (dnimasked - rangh);
	END IF;

	-- devolvemos el dni anonimizado
 	RETURN dnimasked;
END;
$$ LANGUAGE plpgsql;

