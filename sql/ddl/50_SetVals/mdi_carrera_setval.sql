-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mdi_carrera
-- Actualizacion Nro de Secuencia: mdi_carrera_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mdi_carrera_seq',(SELECT MAX(codigo) FROM mdi_carrera));


