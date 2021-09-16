-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - Módulo de Gestión de Encuestas
-- Versión 4.3
-- Tabla: mgi_institucion_tipo
-- Actualizacion Nro de Secuencia: mgi_institucion_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_institucion_tipo_seq',(SELECT MAX(tipo_institucion) FROM mgi_institucion_tipo));


