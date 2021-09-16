-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_grupo_detalle
-- FK: fk_sge_grupo_detalle_sge_encuestado
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_grupo_detalle_sge_encuestado;
CREATE INDEX ifk_sge_grupo_detalle_sge_encuestado ON  sge_grupo_detalle (encuestado);

-- ALTER TABLE sge_grupo_detalle DROP CONSTRAINT fk_sge_grupo_detalle_sge_encuestado; 
ALTER TABLE sge_grupo_detalle 
	ADD CONSTRAINT fk_sge_grupo_detalle_sge_encuestado FOREIGN KEY (encuestado) 
	REFERENCES sge_encuestado (encuestado) deferrable;


