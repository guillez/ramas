-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_respondido_detalle
-- Actualizacion Nro de Secuencia: sge_respondido_detalle_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_respondido_detalle_seq',(SELECT MAX(respondido_detalle) FROM sge_respondido_detalle));


