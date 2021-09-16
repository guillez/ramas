-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_paises
-- FK: fk_mug_paises_mug_continentes
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_paises_mug_continentes;
CREATE INDEX ifk_mug_paises_mug_continentes ON  mug_paises (continente);

-- ALTER TABLE mug_paises DROP CONSTRAINT fk_mug_paises_mug_continentes; 
ALTER TABLE mug_paises 
	ADD CONSTRAINT fk_mug_paises_mug_continentes FOREIGN KEY (continente) 
	REFERENCES mug_continentes (continente) deferrable;


