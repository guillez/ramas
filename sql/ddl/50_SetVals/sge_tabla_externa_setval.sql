-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: sge_tabla_externa
-- Actualizacion Nro de Secuencia: sge_tabla_externa_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tabla_externa_seq',(SELECT MAX(tabla_externa) FROM sge_tabla_externa));


