-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
-- SIU-Kolla 4 - M�dulo de Gesti�n de Encuestas
-- Versi�n 4.3
-- Tabla: mgi_propuesta
-- Actualizacion Nro de Secuencia: mgi_propuesta_seq
-- ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
SELECT setval('mgi_propuesta_seq',(SELECT MAX(propuesta) FROM mgi_propuesta));


