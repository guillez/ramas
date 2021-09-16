-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_elemento_concepto_tipo
-- FK: fk_sge_elemento_concepto_tipo_sge_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_concepto_tipo_sge_elemento;
CREATE INDEX ifk_sge_elemento_concepto_tipo_sge_elemento ON  sge_elemento_concepto_tipo (elemento);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT fk_sge_elemento_concepto_tipo_sge_elemento; 
ALTER TABLE sge_elemento_concepto_tipo 
	ADD CONSTRAINT fk_sge_elemento_concepto_tipo_sge_elemento FOREIGN KEY (elemento) 
	REFERENCES sge_elemento (elemento) deferrable;


