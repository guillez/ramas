-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_tabla_externa
-- Actualizacion Nro de Secuencia: sge_tabla_externa_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_tabla_externa_seq',(SELECT MAX(tabla_externa) FROM sge_tabla_externa));


