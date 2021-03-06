-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M?dulo de Gesti?n de Encuestas
-- Versi?n 4.3
-- Tabla: sge_grupo_detalle
-- FK: fk_sge_grupo_detalle_sge_grupo_definicion
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_detalle_sge_grupo_definicion;
CREATE INDEX ifk_sge_grupo_detalle_sge_grupo_definicion ON  sge_grupo_detalle (grupo);

-- ALTER TABLE sge_grupo_detalle DROP CONSTRAINT fk_sge_grupo_detalle_sge_grupo_definicion; 
ALTER TABLE sge_grupo_detalle 
	ADD CONSTRAINT fk_sge_grupo_detalle_sge_grupo_definicion FOREIGN KEY (grupo) 
	REFERENCES sge_grupo_definicion (grupo) deferrable;


