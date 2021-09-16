-- Este script SQL crea las tablas necesarias para el mask
-- La base de datos donde se crean se pasa como parametro del
-- script install_mask.sh, el cual invoca a este script
DROP SCHEMA IF EXISTS mask CASCADE;
CREATE SCHEMA mask;
CREATE TABLE mask.mask_random_values(
	modulo INTEGER,
	randomval INTEGER
);
-- SELECT mask.init_shuffle_dni(100,40000000);
CREATE TABLE mask.firstnames(
	fnameid INTEGER,
	fname VARCHAR(20),
	gender CHAR(1)
);

CREATE TABLE mask.lastnames(
	lnameid INTEGER,
	lname VARCHAR(30)
);

