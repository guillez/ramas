-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mug_provincias
-- FK: fk_mug_provincias_mug_paises
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_mug_provincias_mug_paises;
CREATE INDEX ifk_mug_provincias_mug_paises ON  mug_provincias (pais);

-- ALTER TABLE mug_provincias DROP CONSTRAINT fk_mug_provincias_mug_paises; 
ALTER TABLE mug_provincias 
	ADD CONSTRAINT fk_mug_provincias_mug_paises FOREIGN KEY (pais) 
	REFERENCES mug_paises (pais) deferrable;


