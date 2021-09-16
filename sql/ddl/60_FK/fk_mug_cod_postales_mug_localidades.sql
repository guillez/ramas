-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_cod_postales
-- FK: fk_mug_cod_postales_mug_localidades
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_cod_postales_mug_localidades;
CREATE INDEX ifk_mug_cod_postales_mug_localidades ON  mug_cod_postales (localidad);

-- ALTER TABLE mug_cod_postales DROP CONSTRAINT fk_mug_cod_postales_mug_localidades; 
ALTER TABLE mug_cod_postales 
	ADD CONSTRAINT fk_mug_cod_postales_mug_localidades FOREIGN KEY (localidad) 
	REFERENCES mug_localidades (localidad) deferrable;


