-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgi_institucion_tipo
-- Actualizacion Nro de Secuencia: mgi_institucion_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_institucion_tipo_seq',(SELECT MAX(tipo_institucion) FROM mgi_institucion_tipo));


