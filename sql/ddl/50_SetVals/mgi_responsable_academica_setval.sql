-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgi_responsable_academica
-- Actualizacion Nro de Secuencia: mgi_responsable_academica_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_responsable_academica_seq',(SELECT MAX(responsable_academica) FROM mgi_responsable_academica));


