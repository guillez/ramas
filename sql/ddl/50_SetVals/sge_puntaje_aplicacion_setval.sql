-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: sge_puntaje_aplicacion
-- Actualizacion Nro de Secuencia: sge_puntaje_aplicacion_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('sge_puntaje_aplicacion_seq',(SELECT MAX(puntaje_aplicacion) FROM sge_puntaje_aplicacion));


