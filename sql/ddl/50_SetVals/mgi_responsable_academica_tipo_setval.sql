-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgi_responsable_academica_tipo
-- Actualizacion Nro de Secuencia: mgi_responsable_academica_tipo_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_responsable_academica_tipo_seq',(SELECT MAX(tipo_responsable_academica) FROM mgi_responsable_academica_tipo));


