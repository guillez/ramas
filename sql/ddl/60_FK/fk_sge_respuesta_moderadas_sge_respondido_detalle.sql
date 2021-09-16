-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_respuesta_moderadas
-- FK: fk_sge_respuesta_moderadas_sge_respondido_detalle
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ifk_sge_respuesta_moderadas_sge_respondido_detalle;
CREATE INDEX ifk_sge_respuesta_moderadas_sge_respondido_detalle ON  sge_respuesta_moderadas (respondido_detalle);

-- ALTER TABLE sge_respuesta_moderadas DROP CONSTRAINT fk_sge_respuesta_moderadas_sge_respondido_detalle; 
ALTER TABLE sge_respuesta_moderadas 
	ADD CONSTRAINT fk_sge_respuesta_moderadas_sge_respondido_detalle FOREIGN KEY (respondido_detalle) 
	REFERENCES sge_respondido_detalle (respondido_detalle) deferrable;


