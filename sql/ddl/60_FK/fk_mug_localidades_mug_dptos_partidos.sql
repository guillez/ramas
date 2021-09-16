-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_localidades
-- FK: fk_mug_localidades_mug_dptos_partidos
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_localidades_mug_dptos_partidos;
CREATE INDEX ifk_mug_localidades_mug_dptos_partidos ON  mug_localidades (dpto_partido);

-- ALTER TABLE mug_localidades DROP CONSTRAINT fk_mug_localidades_mug_dptos_partidos; 
ALTER TABLE mug_localidades 
	ADD CONSTRAINT fk_mug_localidades_mug_dptos_partidos FOREIGN KEY (dpto_partido) 
	REFERENCES mug_dptos_partidos (dpto_partido) deferrable;


