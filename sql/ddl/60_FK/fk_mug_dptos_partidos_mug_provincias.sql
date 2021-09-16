-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_dptos_partidos
-- FK: fk_mug_dptos_partidos_mug_provincias
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_dptos_partidos_mug_provincias;
CREATE INDEX ifk_mug_dptos_partidos_mug_provincias ON  mug_dptos_partidos (provincia);

-- ALTER TABLE mug_dptos_partidos DROP CONSTRAINT fk_mug_dptos_partidos_mug_provincias; 
ALTER TABLE mug_dptos_partidos 
	ADD CONSTRAINT fk_mug_dptos_partidos_mug_provincias FOREIGN KEY (provincia) 
	REFERENCES mug_provincias (provincia) deferrable;


