-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3 
-- Tabla: sge_respondido_detalle
-- Indice: ix_respondido_detalle_moderada
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

-- DROP INDEX ix_respondido_detalle_moderada;
CREATE INDEX ix_respondido_detalle_moderada ON sge_respondido_detalle (moderada);
