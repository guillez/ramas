-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_elemento_concepto_tipo
-- FK: fk_sge_elemento_concepto_tipo_sge_tipo_elemento
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_elemento_concepto_tipo_sge_tipo_elemento;
CREATE INDEX ifk_sge_elemento_concepto_tipo_sge_tipo_elemento ON  sge_elemento_concepto_tipo (tipo_elemento);

-- ALTER TABLE sge_elemento_concepto_tipo DROP CONSTRAINT fk_sge_elemento_concepto_tipo_sge_tipo_elemento; 
ALTER TABLE sge_elemento_concepto_tipo 
	ADD CONSTRAINT fk_sge_elemento_concepto_tipo_sge_tipo_elemento FOREIGN KEY (tipo_elemento) 
	REFERENCES sge_tipo_elemento (tipo_elemento) deferrable;


